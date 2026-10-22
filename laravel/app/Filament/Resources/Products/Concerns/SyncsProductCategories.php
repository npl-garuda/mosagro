<?php
namespace App\Filament\Resources\Products\Concerns;
use Illuminate\Validation\ValidationException;
trait SyncsProductCategories
{
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $primaryId = $this->record->categories()
            ->wherePivot('is_primary', true)
            ->value('categories.id');
        if (! $primaryId) {
            $primaryId = $this->record->categories()->value('categories.id');
        }
        $data['primary_category_id'] = $primaryId;
        $paths = $this->record->images()->orderBy('sort')->orderBy('id')->pluck('path')->all();
        if ($paths === [] && is_string($this->record->image) && $this->record->image !== '') {
            $paths = [$this->record->image];
        }
        $data['image_paths'] = $paths;
        return $data;
    }
    protected function afterSave(): void
    {
        $this->syncPrimaryCategoryPivot();
        $this->syncProductImages();
    }
    protected function afterCreate(): void
    {
        $this->syncPrimaryCategoryPivot();
        $this->syncProductImages();
    }
    protected function syncPrimaryCategoryPivot(): void
    {
        $raw = $this->form->getRawState();
        $categoryIds = collect($raw['categories'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();
        $primaryId = (int) ($raw['primary_category_id'] ?? 0);
        if ($categoryIds === []) {
            throw ValidationException::withMessages([
                'categories' => 'Выберите хотя бы одну категорию.',
            ]);
        }
        if ($primaryId === 0 || ! in_array($primaryId, $categoryIds, true)) {
            throw ValidationException::withMessages([
                'primary_category_id' => 'Основная категория должна быть среди выбранных.',
            ]);
        }
        $sync = [];
        foreach ($categoryIds as $index => $categoryId) {
            $sync[$categoryId] = [
                'is_primary' => $categoryId === $primaryId,
                'sort' => $index,
            ];
        }
        $this->record->categories()->sync($sync);
    }
    protected function syncProductImages(): void
    {
        $raw = $this->form->getRawState();
        $paths = collect($raw['image_paths'] ?? [])
            ->filter(fn ($path) => is_string($path) && $path !== '')
            ->values();
        $this->record->images()->delete();
        foreach ($paths as $index => $path) {
            $this->record->images()->create([
                'path' => $path,
                'is_primary' => $index === 0,
                'sort' => $index,
            ]);
        }
        $this->record->forceFill([
            'image' => $paths->first(),
        ])->saveQuietly();
    }
}
