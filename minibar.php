<?php

class Minibar
{
    private $image;
    private $width;
    private $height;
    private $margin;

    /**
     * Constructor to initialize the Minibar object with default or specified dimensions and margin.
     *
     * @param int $width Width of the image.
     * @param int $height Height of the image.
     * @param int $margin Margin around the graph within the image.
     */
    public function __construct($width = 1000, $height = 500, $margin = 50)
    {
        $this->margin = $margin;
        $this->set_size($width, $height);
        $this->set_background_color(); // Default black background
    }

    /**
     * Converts a hex color string to an associative array of RGB values.
     *
     * @param string $hexColor A color in hexadecimal format.
     * @return array|false Returns an associative array with 'red', 'green', 'blue' keys or false if input is invalid.
     */
    private function hexToRgb($hexColor)
    {
        $hexColor = ltrim($hexColor, '#');
        if (strlen($hexColor) !== 6) {
            return false;
        }
        return [
            'red' => hexdec(substr($hexColor, 0, 2)),
            'green' => hexdec(substr($hexColor, 2, 2)),
            'blue' => hexdec(substr($hexColor, 4, 2))
        ];
    }

    /**
     * Sets the size of the image and creates a new true color image resource.
     *
     * @param int $width Width of the image.
     * @param int $height Height of the image.
     */
    public function set_size($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
        $this->image = imagecreatetruecolor($this->width, $this->height);
    }

    /**
     * Sets the background color of the image.
     *
     * @param int $r Red component value (0-255).
     * @param int $g Green component value (0-255).
     * @param int $b Blue component value (0-255).
     */
    public function set_background_color($r = 0, $g = 0, $b = 0)
    {
        $backgroundColor = imagecolorallocate($this->image, $r, $g, $b);
        imagefill($this->image, 0, 0, $backgroundColor);
    }

    /**
     * Adds a centered label at the top of the image.
     *
     * @param string $text The label text.
     * @param int $r Red component of the label color.
     * @param int $g Green component of the label color.
     * @param int $b Blue component of the label color.
     */
    public function set_label($text, $r = 255, $g = 255, $b = 255)
    {
        $textColor = imagecolorallocate($this->image, $r, $g, $b);
        $font = 5;
        $fontWidth = imagefontwidth($font) * strlen($text);
        $x = ($this->width - $fontWidth) / 2;
        $y = $this->margin / 2;
        imagestring($this->image, $font, $x, $y, $text, $textColor);
    }

    /**
     * Draws lines between data points on the image.
     *
     * @param array $data Associative array of data points.
     * @param string $hexColor Hex color for the line.
     * @param bool $showValues Whether to display values at each data point.
     * @param bool $showKeys Whether to display keys below the graph.
     */
    public function draw_lines($data, $hexColor = "#ff0000", $showValues = true, $showKeys = true)
    {
        $maxValue = max($data);
        $minValue = min($data);
        $color = $this->hexToRgb($hexColor);
        if (!$color) {
            // Fallback to red if color conversion fails
            $color = ['red' => 255, 'green' => 0, 'blue' => 0];
        }
        $graphWidth = $this->width - 2 * $this->margin;
        $graphHeight = $this->height - 2 * $this->margin;

        $this->draw_grid($maxValue, $minValue);

        $previousX = $previousY = null;
        $lineColor = imagecolorallocate($this->image, $color['red'], $color['green'], $color['blue']);

        foreach ($data as $key => $value) {
            $x = $this->margin + (array_search($key, array_keys($data)) / count($data)) * $graphWidth;
            $y = $this->height - $this->margin - (($value - $minValue) / ($maxValue - $minValue)) * $graphHeight;

            if ($previousX !== null && $previousY !== null) {
                imageline($this->image, $previousX, $previousY, $x, $y, $lineColor);
            }

            $previousX = $x;
            $previousY = $y;

            imagefilledellipse($this->image, $x, $y, 5, 5, $lineColor);

            if ($showValues) {
                $valueLabel = $value;
                $textColor = imagecolorallocate($this->image, 200, 200, 200);
                $textY = $y - 15;
                $textWidth = imagefontwidth(2) * strlen($valueLabel);
                $textX = $x - $textWidth / 2;
                imagestring($this->image, 2, $textX, $textY, $valueLabel, $textColor);
            }

            if ($showKeys) {
                $keyLabel = $key;
                $labelY = $this->height - $this->margin + 5;
                $labelWidth = imagefontwidth(2) * strlen($keyLabel);
                $labelX = $x - $labelWidth / 2;
                imagestring($this->image, 2, $labelX, $labelY, $keyLabel, $textColor);
            }
        }
    }

    /**
     * Draws bars for each data point in the dataset.
     *
     * @param array $data Associative array of data points.
     * @param string $hexColor Hex color for the bars.
     * @param bool $showValues Whether to display values on each bar.
     * @param bool $showKeys Whether to display keys below the bars.
     */
    public function draw_bars($data, $hexColor = "#00ff00", $showValues = true, $showKeys = true)
    {
        $maxValue = max($data);
        $minValue = min($data);
        $color = $this->hexToRgb($hexColor);
        if (!$color) {
            // Fallback to green if color conversion fails
            $color = ['red' => 0, 'green' => 255, 'blue' => 0];
        }
        $graphWidth = $this->width - 2 * $this->margin;
        $graphHeight = $this->height - 2 * $this->margin;
        $barWidth = ($graphWidth / count($data)) * 0.8;
        $gap = ($graphWidth / count($data)) * 0.2;

        $this->draw_grid($maxValue, $minValue);

        foreach ($data as $key => $value) {
            $barHeight = (($value - $minValue) / ($maxValue - $minValue)) * $graphHeight;
            $x1 = $this->margin + $gap / 2 + (array_search($key, array_keys($data)) * ($barWidth + $gap));
            $y1 = $this->height - $this->margin - $barHeight;
            $x2 = $x1 + $barWidth;
            $y2 = $this->height - $this->margin;

            $barColor = imagecolorallocate($this->image, $color['red'], $color['green'], $color['blue']);
            imagefilledrectangle($this->image, $x1, $y1, $x2, $y2, $barColor);

            if ($showValues) {
                $valueLabel = $value;
                $textColor = imagecolorallocate($this->image, 200, 200, 200);
                $textY = $y1 - 15;
                $textWidth = imagefontwidth(2) * strlen($valueLabel);
                $textX = $x1 + ($barWidth - $textWidth) / 2;
                imagestring($this->image, 2, $textX, $textY, $valueLabel, $textColor);
            }

            if ($showKeys) {
                $keyLabel = $key;
                $labelY = $this->height - $this->margin + 5;
                $labelWidth = imagefontwidth(2) * strlen($keyLabel);
                $labelX = $x1 + ($barWidth - $labelWidth) / 2;
                imagestring($this->image, 2, $labelX, $labelY, $keyLabel, $textColor);
            }
        }
    }

    /**
     * Draws a grid in the background of the graph.
     *
     * @param float $maxValue The maximum value in the data set.
     * @param float $minValue The minimum value in the data set.
     */
    private function draw_grid($maxValue, $minValue)
    {
        $steps = 10;
        $stepValue = ($maxValue - $minValue) / $steps;
        $graphHeight = $this->height - 2 * $this->margin;
        $yInterval = $graphHeight / $steps;

        $gridColor = imagecolorallocate($this->image, 100, 100, 100);

        for ($i = 0; $i <= $steps; $i++) {
            $y = $this->height - $this->margin - ($yInterval * $i);
            imageline($this->image, $this->margin, $y, $this->width - $this->margin, $y, $gridColor);

            $x = $this->margin + ($this->width - 2 * $this->margin) / $steps * $i;
            imageline($this->image, $x, $this->height - $this->margin, $x, $this->margin, $gridColor);

            $label = round($minValue + ($stepValue * $i), 1);
            $textWidth = imagefontwidth(2) * strlen($label);
            $textX = $this->margin - $textWidth - 10;
            $textY = $y - imagefontheight(2) / 2;
            imagestring($this->image, 2, $textX, $textY, $label, $gridColor);
        }
    }

    /**
     * Outputs the image to the browser with appropriate headers.
     */
    public function render()
    {
        header('Content-Type: image/png');
        imagepng($this->image);
        imagedestroy($this->image);
    }
}
