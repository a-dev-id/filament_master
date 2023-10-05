<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function Laravel\Prompts\select;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()->schema([
                    Forms\Components\Section::make('General')->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('title'),
                            Forms\Components\TextInput::make('slug')
                        ]),
                        Forms\Components\Grid::make(1)->schema([
                            Forms\Components\RichEditor::make('description'),
                        ]),
                    ])->collapsible()->compact(),
                ])->columnSpan(2),

                Forms\Components\Grid::make()->schema([
                    Forms\Components\Section::make('Additional')->schema([
                        Forms\Components\Grid::make(1)->schema([
                            Forms\Components\FileUpload::make('image'),
                            Forms\Components\Select::make('product_category_id')->label('Category')
                                ->live()
                                ->required()
                                ->dehydrated(false)
                                ->options(ProductCategory::pluck('title', 'id')),
                            Forms\Components\Select::make('product_sub_category_id')->label('Sub Category')
                                ->required()
                                ->placeholder(fn (Forms\Get $get): string => empty($get('product_category_id')) ? 'First select sub category' : 'Select an option')
                                ->options(function (Forms\Get $get): Collection {
                                    return ProductSubCategory::where('product_category_id', $get('product_category_id'))->pluck('title', 'id');
                                }),
                            Forms\Components\Toggle::make('status'),
                        ]),
                    ])->collapsible()->compact(),
                ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
