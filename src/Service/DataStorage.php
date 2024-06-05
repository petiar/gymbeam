<?php

namespace App\Service;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class DataStorage {
    private array $data;
    public function __construct(
        protected DataAnalyzer $dataAnalyzer
    ) {
        $file = $this->getFile();
        if (($handle = fopen($file->getPathname(), "r")) !== false) {
            while (($row = fgetcsv($handle)) !== false) {
                $sentiment = $this->dataAnalyzer->getSentiment(strip_tags($row[1]));
                $this->data[] = [
                    'name' => $row[0],
                    'description' => $row[1],
                    'sentiment' => $sentiment,
                ];
            }
            fclose($handle);
        }
        // the first line is column description, we don't want that in data
        array_shift($this->data);
    }

    public function getData(): array {
        return $this->data;
    }

    public function getMaxSentimentKey($type = 'neu'): int {
        $max = 0;
        foreach ($this->data as $key => $row) {
            $max = $row['sentiment'][$type] > $this->data[$max]['sentiment'][$type] ? $key : $max;
        }
        return $max;
    }

    private function getFile(): SplFileInfo {
        $finder = new Finder();
        $files = $finder->files()->in('../src')->name('products.csv');
        $iterator = $files->getIterator();
        $iterator->rewind();
        return $iterator->current();
    }
}