<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductSubCategoryResource\Pages;
use App\Filament\Resources\ProductSubCategoryResource\RelationManagers;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class ProductSubCategoryResource extends Resource
{
    protected static ?string $model = ProductSubCategory::class;

    // protected static ?string $navigationIcon = 'heroicon-o-hashtag';
    protected static ?string $navigationLabel = 'Sub Categories';
    protected static ?string $navigationGroup = 'Products';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                    Forms\Components\TextInput::make('slug'),
                ]),
                Forms\Components\Grid::make(1)->schema([
                    Forms\Components\Select::make('product_category_id')
                        ->label('Category')
                        ->options(ProductCategory::all()->pluck('title', 'id'))
                        ->searchable()
                        ->preload(),
                    Forms\Components\RichEditor::make('description'),
                    Forms\Components\FileUpload::make('image'),
                    Forms\Components\Toggle::make('status'),
                ]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('ProductCategory.title')->label('Category')->searchable()->sortable(),
                Tables\Columns\ToggleColumn::make('status')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProductSubCategories::route('/'),
        ];
    }
}
