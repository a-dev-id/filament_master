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
use Illuminate\Support\Collection;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Support\RawJs;

use function Laravel\Prompts\select;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    // protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Lists';
    protected static ?string $navigationGroup = 'Products';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()->schema([
                    Forms\Components\Section::make('General')->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('title')
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                            Forms\Components\TextInput::make('slug')
                        ]),
                        Forms\Components\Grid::make(1)->schema([
                            Forms\Components\TextInput::make('price'),
                            Forms\Components\RichEditor::make('description'),
                        ]),
                    ])->collapsible()->compact(),
                ])->columnSpan(2),
                Forms\Components\Grid::make()->schema([
                    Forms\Components\Section::make('Additional')->schema([
                        Forms\Components\Grid::make(1)->schema([
                            Forms\Components\FileUpload::make('image'),
                            Forms\Components\Select::make('product_category_id')
                                ->label('Category')
                                ->required()
                                ->options(ProductCategory::pluck('title', 'id'))
                                ->searchable()
                                ->preload()
                                ->live(),
                            Forms\Components\Select::make('product_sub_category_id')
                                ->required()
                                ->label('Sub Category')
                                ->placeholder(fn (Forms\Get $get): string => empty($get('product_category_id')) ? 'First select Category' : 'Select an option')
                                ->options(function (Forms\Get $get): Collection {
                                    return ProductSubCategory::where('product_category_id', $get('product_category_id'))->pluck('title', 'id');
                                })
                                ->searchable()
                                ->preload(),
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
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('price')->money('IDR'),
                Tables\Columns\TextColumn::make('ProductCategory.title')->label('Category')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('ProductSubCategory.title')->label('Sub Category')->sortable()->searchable(),
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
