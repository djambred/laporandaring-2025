<?php

namespace App\Filament\Admin\Resources\JadwalResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AbsensisRelationManager extends RelationManager
{
    protected static string $relationship = 'absensis';

    protected static ?string $title = 'Daftar Mahasiswa Yang Sudah Absen';

    protected static ?string $label = 'Absensi';

    protected static ?string $pluralLabel = 'Absensi';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('mahasiswa_id')
                    ->label('Mahasiswa')
                    ->relationship('mahasiswa', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'alpha' => 'Alpha',
                    ])
                    ->required(),
                Forms\Components\DateTimePicker::make('waktu_absen')
                    ->label('Waktu Absen')
                    ->required()
                    ->default(now()),
                Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('mahasiswa.nama')
            ->columns([
                Tables\Columns\TextColumn::make('mahasiswa.npm')
                    ->label('NPM')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('NPM tersalin!')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hadir' => 'success',
                        'izin' => 'warning',
                        'sakit' => 'info',
                        'alpha' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => strtoupper($state)),
                Tables\Columns\TextColumn::make('waktu_absen')
                    ->label('Waktu Absen')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->wrap()
                    ->limit(50)
                    ->toggleable(),
                Tables\Columns\ImageColumn::make('foto_absen')
                    ->label('Foto')
                    ->circular()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'alpha' => 'Alpha',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Absensi Manual'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('waktu_absen', 'desc')
            ->emptyStateHeading('Belum Ada Mahasiswa Yang Absen')
            ->emptyStateDescription('Mahasiswa dapat melakukan absensi melalui website atau Anda dapat menambahkan absensi manual.')
            ->emptyStateIcon('heroicon-o-user-group');
    }
}
