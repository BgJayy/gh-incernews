<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Models\Article;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tulisan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->hint(fn (?string $state): string => mb_strlen((string) $state).' / 70')
                            ->hintColor(fn (?string $state): string => mb_strlen((string) $state) > 70 ? 'warning' : 'gray')
                            ->helperText('Ideal di bawah 70 karakter. Kata kunci utama di depan.')
                            ->afterStateUpdated(function (?string $state, Set $set, Get $get): void {
                                if (blank($get('slug'))) {
                                    $set('slug', Str::slug((string) $state));
                                }

                                if (blank($get('seo_title'))) {
                                    $set('seo_title', Str::limit(trim((string) $state), 60, ''));
                                }
                            })
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->rules([
                                Rule::notIn(Article::reservedSlugs()),
                                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                            ])
                            ->validationMessages([
                                'not_in' => 'Slug ini dipakai halaman situs (cari, admin, kategori) dan tidak bisa dipakai artikel.',
                                'regex' => 'Slug hanya boleh huruf kecil, angka, dan tanda hubung.',
                            ])
                            ->prefix(rtrim((string) config('app.url'), '/').'/')
                            ->helperText('Otomatis dari judul. Jangan memakai cari, admin, atau kategori.')
                            ->columnSpanFull(),
                        Textarea::make('excerpt')
                            ->label('Ringkasan')
                            ->required()
                            ->rows(3)
                            ->maxLength(280)
                            ->live(onBlur: true)
                            ->hint(fn (?string $state): string => mb_strlen((string) $state).' / 200')
                            ->hintColor(fn (?string $state): string => mb_strlen((string) $state) > 200 ? 'warning' : 'gray')
                            ->helperText('Tampil di kartu beranda, kanal, dan cuplikan pencarian. Ideal sekitar 150–200 karakter.')
                            ->afterStateUpdated(function (?string $state, Set $set, Get $get): void {
                                if (blank($get('seo_description'))) {
                                    $set('seo_description', Str::limit(trim((string) $state), 160, ''));
                                }
                            })
                            ->columnSpanFull(),
                        RichEditor::make('body')
                            ->label('Isi')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('articles/body')
                            ->fileAttachmentsVisibility('public')
                            ->fileAttachmentsAcceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                            ->helperText('Gambar di dalam artikel diunggah ke penyimpanan publik dan tampil di halaman berita.')
                            ->columnSpanFull(),
                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('author_id')
                            ->label('Penulis')
                            ->relationship('author', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                    ]),
                Section::make('Gambar utama')
                    ->schema([
                        FileUpload::make('featured_image')
                            ->label('Berkas gambar')
                            ->disk('public')
                            ->directory('articles')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(4096)
                            ->helperText('Wajib jika status Terbit. Rasio 16:9, maksimal 4 MB.')
                            ->required(fn (Get $get): bool => $get('status') === 'published')
                            ->columnSpanFull(),
                        TextInput::make('featured_image_alt')
                            ->label('Teks alternatif')
                            ->maxLength(160)
                            ->hint(fn (?string $state): string => mb_strlen((string) $state).' / 125')
                            ->hintColor(fn (?string $state): string => mb_strlen((string) $state) > 125 ? 'warning' : 'gray')
                            ->helperText('Wajib jika ada gambar atau jika artikel diterbitkan. Untuk pembaca layar dan Google Images — jangan salin judul mentah.')
                            ->required(fn (Get $get): bool => filled($get('featured_image')) || $get('status') === 'published'),
                        TextInput::make('featured_image_caption')
                            ->label('Kredit foto')
                            ->maxLength(255)
                            ->helperText('Opsional. Tampil di bawah gambar pada halaman artikel.'),
                    ]),
                Section::make('Penerbitan')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->options([
                                'draft' => 'Draf',
                                'published' => 'Terbit',
                            ])
                            ->default('draft')
                            ->required()
                            ->live()
                            ->helperText('Pilih Terbit hanya setelah gambar dan teks alternatif terisi.'),
                        DateTimePicker::make('published_at')
                            ->label('Tanggal terbit')
                            ->seconds(false)
                            ->default(now()),
                        Toggle::make('is_featured')
                            ->label('Tampil di slider beranda'),
                        Toggle::make('is_trending')
                            ->label('Tampil di trending'),
                    ]),
                Section::make('SEO')
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('Judul SEO')
                            ->maxLength(70)
                            ->hint(fn (?string $state): string => mb_strlen((string) $state).' / 60')
                            ->hintColor(fn (?string $state): string => mb_strlen((string) $state) > 60 ? 'warning' : 'gray')
                            ->helperText('Kosongkan jika ingin memakai judul artikel. Ideal ±60 karakter.'),
                        Textarea::make('seo_description')
                            ->label('Deskripsi SEO')
                            ->rows(2)
                            ->maxLength(180)
                            ->hint(fn (?string $state): string => mb_strlen((string) $state).' / 160')
                            ->hintColor(fn (?string $state): string => mb_strlen((string) $state) > 160 ? 'warning' : 'gray')
                            ->helperText('Kosongkan jika ingin memakai ringkasan. Ideal 150–160 karakter.'),
                    ]),
            ]);
    }
}
