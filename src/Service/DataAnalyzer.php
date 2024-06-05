<?php

namespace App\Service;

use Sentiment\Analyzer;

class DataAnalyzer {
    public function getSentiment(string $data): array {
        $analyzer = new Analyzer();
        return $analyzer->getSentiment($data);
    }
}