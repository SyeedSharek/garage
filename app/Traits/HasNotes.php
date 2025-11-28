<?php

namespace App\Traits;

use App\Models\Note;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasNotes
{
    /**
     * Get all notes for this model
     */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable')->orderBy('created_at', 'desc');
    }

    /**
     * Get only important notes
     */
    public function importantNotes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable')
            ->where('is_important', true)
            ->orderBy('created_at', 'desc');
    }

    /**
     * Add a note to this model
     *
     * @param string $content
     * @param bool $isImportant
     * @param int|null $userId
     * @return Note
     */
    public function addNote(string $content, bool $isImportant = false, ?int $userId = null): Note
    {
        return $this->notes()->create([
            'content' => $content,
            'is_important' => $isImportant,
            'user_id' => $userId,
        ]);
    }

    /**
     * Add an important note
     *
     * @param string $content
     * @param int|null $userId
     * @return Note
     */
    public function addImportantNote(string $content, ?int $userId = null): Note
    {
        return $this->addNote($content, true, $userId);
    }

    /**
     * Get the latest note
     *
     * @return Note|null
     */
    public function latestNote(): ?Note
    {
        return $this->notes()->latest()->first();
    }

    /**
     * Check if model has notes
     *
     * @return bool
     */
    public function hasNotes(): bool
    {
        return $this->notes()->exists();
    }

    /**
     * Check if model has important notes
     *
     * @return bool
     */
    public function hasImportantNotes(): bool
    {
        return $this->importantNotes()->exists();
    }

    /**
     * Get notes count
     *
     * @return int
     */
    public function notesCount(): int
    {
        return $this->notes()->count();
    }
}

