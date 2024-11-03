<?php

$data = [
    ['vendas' => 100, 'custos' => 80, 'lucro' => 20],
    ['vendas' => 150, 'custos' => 100, 'lucro' => 50],
    ['vendas' => 120, 'custos' => 90, 'lucro' => 30]
];

$analyzer = new DataAnalyzer($data);
$analyzer->preprocessData();

// Obtém estatísticas básicas
$stats = $analyzer->getBasicStats();

// Detecta anomalias
$anomalias = $analyzer->detectAnomalies();

// Calcula correlações
$correlacoes = $analyzer->calculateCorrelations();

namespace DataAnalysis;
//include "conn.php";
class DataAnalyzer {
    private $data;
    private $preprocessedData;
    
    public function __construct(array $data) {
        $this->data = $data;
    }
    
    // Pré-processamento dos dados
    public function preprocessData(): void {
        $this->preprocessedData = array_map(function($row) {
            // Remove valores nulos
            $row = array_filter($row, function($value) {
                return !is_null($value);
            });
            
            // Normaliza valores numéricos
            foreach ($row as $key => $value) {
                if (is_numeric($value)) {
                    $row[$key] = floatval($value);
                }
            }
            
            return $row;
        }, $this->data);
    }
    
    // Análise estatística básica
    public function getBasicStats(): array {
        $numericColumns = $this->getNumericColumns();
        $stats = [];
        
        foreach ($numericColumns as $column) {
            $values = array_column($this->preprocessedData, $column);
            $stats[$column] = [
                'mean' => $this->calculateMean($values),
                'median' => $this->calculateMedian($values),
                'std_dev' => $this->calculateStdDev($values),
                'min' => min($values),
                'max' => max($values)
            ];
        }
        
        return $stats;
    }
    
    // Identificação de anomalias usando o método Z-score
    public function detectAnomalies(float $threshold = 3.0): array {
        $anomalies = [];
        $numericColumns = $this->getNumericColumns();
        
        foreach ($numericColumns as $column) {
            $values = array_column($this->preprocessedData, $column);
            $mean = $this->calculateMean($values);
            $stdDev = $this->calculateStdDev($values);
            
            foreach ($values as $index => $value) {
                $zScore = abs(($value - $mean) / $stdDev);
                if ($zScore > $threshold) {
                    $anomalies[] = [
                        'column' => $column,
                        'index' => $index,
                        'value' => $value,
                        'z_score' => $zScore
                    ];
                }
            }
        }
        
        return $anomalies;
    }
    
    // Análise de correlação entre variáveis
    public function calculateCorrelations(): array {
        $numericColumns = $this->getNumericColumns();
        $correlations = [];
        
        foreach ($numericColumns as $col1) {
            foreach ($numericColumns as $col2) {
                if ($col1 !== $col2) {
                    $values1 = array_column($this->preprocessedData, $col1);
                    $values2 = array_column($this->preprocessedData, $col2);
                    $correlations[$col1][$col2] = $this->calculatePearsonCorrelation($values1, $values2);
                }
            }
        }
        
        return $correlations;
    }
    
    // Funções auxiliares
    private function getNumericColumns(): array {
        $columns = [];
        foreach ($this->preprocessedData[0] as $key => $value) {
            if (is_numeric($value)) {
                $columns[] = $key;
            }
        }
        return $columns;
    }
    
    private function calculateMean(array $values): float {
        return array_sum($values) / count($values);
    }
    
    private function calculateMedian(array $values): float {
        sort($values);
        $count = count($values);
        $mid = floor(($count - 1) / 2);
        return ($count % 2) ? $values[$mid] : ($values[$mid] + $values[$mid + 1]) / 2;
    }
    
    private function calculateStdDev(array $values): float {
        $mean = $this->calculateMean($values);
        $variance = array_reduce($values, function($carry, $item) use ($mean) {
            return $carry + pow($item - $mean, 2);
        }, 0) / count($values);
        return sqrt($variance);
    }
    
    private function calculatePearsonCorrelation(array $x, array $y): float {
        $meanX = $this->calculateMean($x);
        $meanY = $this->calculateMean($y);
        
        $numerator = array_sum(array_map(function($xi, $yi) use ($meanX, $meanY) {
            return ($xi - $meanX) * ($yi - $meanY);
        }, $x, $y));
        
        $denominator = sqrt(
            array_sum(array_map(function($xi) use ($meanX) {
                return pow($xi - $meanX, 2);
            }, $x)) *
            array_sum(array_map(function($yi) use ($meanY) {
                return pow($yi - $meanY, 2);
            }, $y))
        );
        
        return $denominator == 0 ? 0 : $numerator / $denominator;
    }
}