<?php

namespace App\Filament\Resources\UnitOrderResource\Pages;

use App\Filament\Resources\UnitOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use App\Models\UnitOrder;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
class EditUnitOrder extends EditRecord
{
    protected static string $resource = UnitOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getActions(): array
    {
        return [
            Action::make('send_istisna_contract')
                ->label('إرسال إشعار بعقد الاستصناع')
                ->requiresConfirmation()
                ->action(function (UnitOrder $record) {
                    if(!$record->istisna_contract_url) {
                        Notification::make()->title('خطأ: لم يتم رفع ملف العقد بعد.')->danger()->send();
                        return;
                    }
                    $record->user->notify(new \App\Notifications\DocumentReady(
                        'عقد الاستصناع جاهز',
                        'عقد الاستصناع الخاص بوحدتك جاهز الآن للتحميل.',
                        route('download.document', ['order' => $record->id, 'type' => 'istisna']) // مثال لرابط التحميل
                    ));
                    Notification::make()->title('تم إرسال الإشعار بنجاح!')->success()->send();
                })
                ->visible(fn(UnitOrder $record) => $record->istisna_contract_url),

            Action::make('send_price_quote')
                ->label('إرسال إشعار بعرض السعر')
                ->requiresConfirmation()
                ->action(function (UnitOrder $record) {
                    if(!$record->price_quote_url) {
                        Notification::make()->title('خطأ: لم يتم رفع ملف عرض السعر بعد.')->danger()->send();
                        return;
                    }
                    $record->user->notify(new \App\Notifications\DocumentReady(
                        'عرض السعر جاهز',
                        'عرض السعر الخاص بتمويل وحدتك جاهز الآن للتحميل.',
                        route('download.document', ['order' => $record->id, 'type' => 'quote']) // مثال
                    ));
                    Notification::make()->title('تم إرسال الإشعار بنجاح!')->success()->send();
                })
                ->visible(fn(UnitOrder $record) => $record->price_quote_url),
        ];
    }
}
