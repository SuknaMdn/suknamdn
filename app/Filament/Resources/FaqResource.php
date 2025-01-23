<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Filament\Resources\FaqResource\RelationManagers;
use App\Models\Faq;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Faq';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('question')
                    ->required()
                    ->columnSpan('full'),

                Textarea::make('answer')
                    ->required()
                    ->columnSpan('full'),

                Select::make('category')
                    ->options([
                        'general' => 'General',
                        'technical' => 'Technical',
                        'billing' => 'Billing'
                    ]),

                TextInput::make('order')
                    ->numeric()
                    ->default(0),

                Toggle::make('is_active')
                    ->default(true)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('question')
                ->searchable()
                ->sortable(),

            TextColumn::make('category')
                ->searchable(),

            BooleanColumn::make('is_active')
                ->label('Active'),

            TextColumn::make('order')
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaqs::route('/'),
            // 'create' => Pages\CreateFaq::route('/create'),
            // 'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
