<?php

class PagesModel
{
    private PDO $pdo;
    private FieldsModel $fieldsModel;
    private RepeatersModel $repeatersModel;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->fieldsModel = new FieldsModel($pdo);
        $this->repeatersModel = new RepeatersModel($pdo);
    }

    public function get(string $slug): array
    {
        $stmt = $this->pdo->prepare("SELECT id, title, slug FROM pages WHERE slug = :slug");
        $stmt->execute(['slug' => $slug]);
        $page = $stmt->fetch();

        if (!$page) {
            return [];
        }

        $page_id = $page['id'];


        $fields = $this->fieldsModel->getPageFields($page_id);

        $repeatersRaw = $this->repeatersModel->getPageRepeaters($page_id);

        $repeaterIds = array_column($repeatersRaw, 'id');

        $repeaterFields = [];
        if (count($repeaterIds) > 0) {
            $in  = str_repeat('?,', count($repeaterIds) - 1) . '?';
            $stmt = $this->pdo->prepare("SELECT id, repeater_id, field_key, value, type,title, sort_order FROM repeater_fields WHERE repeater_id IN ($in) ORDER BY sort_order, id");
            $stmt->execute($repeaterIds);
            $fieldsRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($fieldsRaw as $f) {
                $repeaterFields[$f['repeater_id']][] = [
                    'id' => $f['id'],
                    'title' => $f['title'],
                    'key' => $f['field_key'],
                    'value' => $f['value'],
                    'type' => $f['type'],
                    'sort_order' => $f['sort_order'],
                ];
            }
        }

        $repeaters = [];
        foreach ($repeatersRaw as $r) {
            $repeaters[] = [
                'id' => $r['id'],
                'key' => $r['repeater_key'],
                'title' => $r['title'],
                'page_id' => $r['page_id'],
                'sort_order' => $r['sort_order'],
                'fields' => $repeaterFields[$r['id']] ?? [],
            ];
        }

        return [
            'id' => $page['id'],
            'title' => $page['title'],
            'slug' => $page['slug'],
            'fields' => $fields,
            'repeaters' => $repeaters,
        ];
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM `pages` ORDER BY `id` DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllWithFields(): array
    {
        $stmt = $this->pdo->prepare("
        SELECT 
            p.id AS page_id,
            p.title AS page_title,
            p.slug,
            f.id AS field_id,
            f.field_key,
            f.value,
            f.title,
            f.type
        FROM pages p
        LEFT JOIN fields f ON f.page_id = p.id
        ORDER BY p.id, f.id
    ");
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $pages = [];
        foreach ($rows as $row) {
            $pageId = $row['page_id'];
            if (!isset($pages[$pageId])) {
                $pages[$pageId] = [
                    'id' => $pageId,
                    'title' => $row['page_title'],
                    'slug' => $row['slug'],
                    'fields' => [],
                    'repeaters' => [],
                ];
            }
            if ($row['field_id']) {
                $pages[$pageId]['fields'][] = [
                    'id' => $row['field_id'],
                    'value' => $row['value'],
                    'type' => $row['type'],
                    'title' => $row['title'],
                    'field_key' => $row['field_key'],
                ];
            }
        }

        if (empty($pages)) {
            return [];
        }

        $pageIds = array_keys($pages);
        $in  = str_repeat('?,', count($pageIds) - 1) . '?';
        $stmt = $this->pdo->prepare("SELECT id, page_id, repeater_key, sort_order FROM repeaters WHERE page_id IN ($in) ORDER BY page_id, sort_order, id");
        $stmt->execute($pageIds);
        $repeatersRaw = $stmt->fetchAll();

        if ($repeatersRaw) {
            $repeaterIds = [];
            foreach ($repeatersRaw as $r) {
                $pages[$r['page_id']]['repeaters'][$r['id']] = [
                    'id' => $r['id'],
                    'key' => $r['repeater_key'],
                    'page_id' => $r['page_id'],
                    'title' => $r['title'],
                    'sort_order' => $r['sort_order'],
                    'fields' => [],
                ];
                $repeaterIds[] = $r['id'];
            }

            if ($repeaterIds) {
                $in = str_repeat('?,', count($repeaterIds) - 1) . '?';
                $stmt = $this->pdo->prepare("SELECT id, repeater_id, field_key, value, type, sort_order FROM repeater_fields WHERE repeater_id IN ($in) ORDER BY sort_order, id");
                $stmt->execute($repeaterIds);
                $repeaterFieldsRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($repeaterFieldsRaw as $f) {
                    $pagesReps = &$pages;

                    foreach ($pagesReps as &$page) {
                        if (isset($page['repeaters'][$f['repeater_id']])) {
                            $page['repeaters'][$f['repeater_id']]['fields'][] = [
                                'id' => $f['id'],
                                'key' => $f['field_key'],
                                'value' => $f['value'],
                                'type' => $f['type'],
                                'sort_order' => $f['sort_order'],
                            ];
                            break;
                        }
                    }
                }
            }

            foreach ($pages as &$page) {
                $page['repeaters'] = array_values($page['repeaters']);
            }
        }

        return array_values($pages);
    }

    public function create($data): void
    {
        $sql = "INSERT INTO `pages` (slug, title) VALUES (:slug,:title)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'slug' => $data['slug'],
            'title' => $data['title']
        ]);
    }

    public function update($data): void
    {
        $sql = "UPDATE `pages` SET `title` = :title WHERE `slug` = :slug";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'slug' => $data['slug'],
            'title' => $data['title']
        ]);
    }

    public function getById(int $id): array
    {
        $sql = "SELECT * FROM `pages` WHERE `id` = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
        return $stmt->fetch();
    }

    public function delete(string $slug): void
    {
        $sql = "DELETE FROM `pages` WHERE `slug` = :slug";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
    }


}