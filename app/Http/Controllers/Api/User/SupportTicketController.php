<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Nullable;

class SupportTicketController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $query = SupportTicket::with(['user', 'assignedTo', 'latestMessage'])
            ->where('user_id', $user->id);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $tickets = $query->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        $transformed = $tickets->getCollection()->transform(function ($ticket) {
            return [
                'id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'subject' => $ticket->subject,
                'description' => $ticket->description,
                'status' => $ticket->status,
                'priority' => $ticket->priority,
                'category' => $ticket->category,
                'created_at' => $ticket->created_at,
                'user' => [
                    'username' => $ticket->user->username,
                ],
                'latest_message' => $ticket->latestMessage ? [
                    'message' => $ticket->latestMessage->message,
                    'created_at' => $ticket->latestMessage->created_at,
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $transformed,
            'pagination' => [
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
                'per_page' => $tickets->perPage(),
                'total' => $tickets->total(),
            ]
        ]);
    }


    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'category' => 'required|in:property_inquiry,technical_issue,billing,general',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
            'category' => $request->category,
            'user_type' => 'customer',
        ]);

        // Create initial message
        $message = SupportMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->description,
        ]);

        // Handle attachments
        // if ($request->hasFile('attachments')) {
        //     $this->handleAttachments($request->file('attachments'), $message);
        // }

        $ticket->load(['user', 'messages.user', ]); //'messages.attachments'

        return response()->json([
            'success' => true,
            'message' => 'Support ticket created successfully',
            'data' => $ticket
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $ticket = SupportTicket::with(['user', 'assignedTo', 'messages.user']) //, 'messages.attachments'
            ->where('user_id', Auth::id())
            ->where('id',$id)->first();
        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'subject' => $ticket->subject,
                'description' => $ticket->description,
                'status' => $ticket->status,
                'priority' => $ticket->priority,
                'category' => $ticket->category,
                'created_at' => $ticket->created_at,
                'user' => [
                    'username' => $ticket->user->username,
                    'firstname' => $ticket->user->firstname,
                    'lastname' => $ticket->user->lastname,
                    'phone' => $ticket->user->phone,
                ],
                'messages' => $ticket->messages->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'message' => $message->message,
                        'is_internal' => $message->is_internal,
                        'created_at' => $message->created_at,
                        'user' => [
                            'username' => $message->user->username,
                        ],
                    ];
                }),
            ]
        ]);

    }

    public function addMessage(Request $request, $ticketId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
            // 'attachments.*' => 'file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $ticket = SupportTicket::where('user_id', Auth::id())
            ->findOrFail($ticketId);

        $message = SupportMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Handle attachments
        // if ($request->hasFile('attachments')) {
        //     $this->handleAttachments($request->file('attachments'), $message);
        // }

        // Update ticket status if it was closed
        if ($ticket->status === 'closed') {
            $ticket->update(['status' => 'open']);
        }

        $message->load(['user']); //, 'attachments'

        return response()->json([
            'success' => true,
            'message' => 'Message added successfully',
            'data' => [
                'id' => $message->id,
                'message' => $message->message,
                'created_at' => $message->created_at,
                'user' => [
                    'username' => $message->user->username,
                ]
            ]
        ], 201);
    }

    public function getMessages($ticketId): JsonResponse
    {
        $ticket = SupportTicket::where('user_id', Auth::id())
            ->findOrFail($ticketId);

        $messages = SupportMessage::with('user')
            ->where('ticket_id', $ticket->id)
            ->where('is_internal', false)
            ->orderBy('created_at', 'asc')
            ->get()
            ->transform(function ($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'created_at' => $message->created_at,
                    'user' => [
                        'username' => $message->user->username,
                        'firstname' => $message->user->firstname,
                        'lastname' => $message->user->lastname,
                        'phone' => $message->user->phone,

                    ],
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $messages,
        ]);
    }


    public function close($id): JsonResponse
    {
        $ticket = SupportTicket::where('user_id', Auth::id())
            ->where('id',$id)->first();
        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found'
            ], 404);
        }
        $ticket->update([
            'status' => 'closed',
            'resolved_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ticket closed successfully'
        ]);
    }

    // private function handleAttachments($files, $message)
    // {
    //     foreach ($files as $file) {
    //         $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    //         $path = $file->storeAs('support_attachments', $filename, 'public');

    //         $message->attachments()->create([
    //             'filename' => $filename,
    //             'original_name' => $file->getClientOriginalName(),
    //             'mime_type' => $file->getMimeType(),
    //             'size' => $file->getSize(),
    //             'path' => $path,
    //         ]);
    //     }
    // }
}
