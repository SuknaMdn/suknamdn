<?php

namespace App\Filament\Resources\SupportTicketResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MessagesRelationManagerRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ticket_number')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('message')
                    ->wrap()
                    ->limit(80)
                    ->label('Message'),

                Tables\Columns\TextColumn::make('user.username')
                    ->label('Sender'),

                Tables\Columns\IconColumn::make('is_internal')
                    ->boolean()
                    ->label('Internal'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Sent At')
                    ->dateTime(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
