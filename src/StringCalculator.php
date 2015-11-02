<?php

class StringCalculator
{
    /**
     * @param string $numbers
     * @return number
     * @throws Exception
     */
    public function add($numbers)
    {
        $delimiters = array();
        $delimiters[] = "\n";

        if ($this->hasCustomDelimiter($numbers, $customDelimiter)) {
            $numbers = $this->stripCustomDelimiter($numbers);
            if ($this->hasMultipleDelimiters($customDelimiter, $customDelimiters)) {
                $delimiters = array_merge($delimiters, $customDelimiters);
            } else {
                $delimiters[] = $customDelimiter;
            }
        }

        $numbers = $this->splitNumbers($numbers, $delimiters);
        $numbers = $this->ignoreGreaterThan1000($numbers);
        $this->guardNoNegativeNumbers($numbers);

        return array_sum($numbers);
    }

    /**
     * @param string $numbers
     * @param null|string $delimiter
     * @return bool
     */
    private function hasCustomDelimiter($numbers, &$delimiter = null)
    {
        if (preg_match("/^\/\/(.*)\n/", $numbers, $matches)) {
            $delimiter = $matches[1];
            return true;
        }

        return false;
    }

    private function stripCustomDelimiter($numbers)
    {
        return preg_replace("/^\/\/(.*)\n/", '', $numbers);
    }

    /**
     * @param string $customDelimiter
     * @param array $customDelimiters
     * @return bool
     */
    private function hasMultipleDelimiters($customDelimiter, &$customDelimiters)
    {
        $customDelimiters = array();
        if (preg_match_all("/(?:\[(.*)\])+/U", $customDelimiter, $matches)) {
            foreach ($matches[1] as $delimiter) {
                $customDelimiters[] = $delimiter;
            }
            return true;
        }
        return false;
    }

    /**
     * @param $numbers
     * @param $delimiters
     * @return array|mixed
     */
    private function splitNumbers($numbers, $delimiters)
    {
        foreach ($delimiters as $delimiter) {
            $numbers = str_replace($delimiter, ',', $numbers);
        }
        $numbers = explode(',', $numbers);
        return $numbers;
    }

    /**
     * @param $numbers
     * @return array
     */
    private function ignoreGreaterThan1000($numbers)
    {
        $numbers = array_filter($numbers, function ($number) {
            return ($number <= 1000);
        });
        return $numbers;
    }

    /**
     * @param $numbers
     * @throws Exception
     */
    private function guardNoNegativeNumbers($numbers)
    {
        $negatives = array_filter($numbers, function ($number) {
            return ($number < 0);
        });
        if (count($negatives) > 0) {
            throw new Exception('negatives not allowed (' . implode(',', $negatives) . ')');
        }
    }

}