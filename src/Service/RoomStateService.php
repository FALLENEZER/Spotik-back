<?php

namespace App\Service;

/**
 * Временное in-memory хранилище состояния комнат.
 * Пока нет полноценной модели/БД для комнат, это позволит фронту работать.
 * Состояние живёт в памяти процесса PHP (перезапускается при рестарте).
 */
class RoomStateService
{
    /**
     * @var array<string, array>
     */
    private array $rooms = [];

    /**
     * Получить комнату (создать по умолчанию, если нет).
     */
    public function getRoom(string $id): array
    {
        if (!isset($this->rooms[$id])) {
            $this->rooms[$id] = $this->makeDefaultRoom($id);
        }

        return $this->rooms[$id];
    }

    /**
     * Гарантировать наличие участника в комнате. Возвращает текущее состояние комнаты.
     * $participant ожидается в формате: [id => int, name => string, isHost? => bool]
     */
    public function ensureParticipant(string $roomId, array $participant): array
    {
        $room = $this->getRoom($roomId);

        $exists = false;
        foreach ($room['participants'] as $p) {
            if ((int)$p['id'] === (int)$participant['id']) {
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            // Первый участник становится хостом
            if (empty($room['participants'])) {
                $participant['isHost'] = $participant['isHost'] ?? true;
            }
            $room['participants'][] = $participant;
        }

        // сохранить изменения
        $this->rooms[$roomId] = $room;

        return $room;
    }

    /**
     * Установить (обновить) текущее состояние комнаты целиком.
     */
    public function setRoom(string $id, array $room): void
    {
        $this->rooms[$id] = $room;
    }

    private function makeDefaultRoom(string $id): array
    {
        return [
            'id' => $id,
            'name' => ucfirst($id) . ' Room',
            'participants' => [],
            'currentTrack' => null,
            'queue' => [],
        ];
    }
}
