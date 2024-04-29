# Minibar Graph Library 📊

Welcome to the Minibar Graph Library, an intuitive and efficient solution for generating dynamic bar and line graphs. Tailored to facilitate the creation and visualization of statistical data, this PHP library is perfect for applications that require graphical data representations.

## Features 🌟
- Dynamic Image Creation: Generate images with dynamic data visualization using true color.
- Customizable Dimensions: Adjust the width, height, and margins of your graphs for optimal fitting.
- Hex Color Support: Customize the colors of your graphs with hexadecimal values.
- Grid Background: Enhance readability with a grid background that adjusts based on your data range.
- Labels and Annotations: Add labels and annotations to improve the comprehensibility of your graphs.

## Installation 🛠
To use the Minibar Graph Library, simply download the `minibar.php` file and include it in your PHP project.
```
require_once 'Minibar.php';
```
## Usage Example 🔍
Below is a basic usage example to get you started with generating a bar graph.
```
// Create a new Minibar instance
$graph = new Minibar(1200, 600);

// Add a label to the graph
$graph->set_label("Monthly Sales Data");

// Sample data
$data = [
    "January" => 100,
    "February" => 150,
    "March" => 125,
    "April" => 175,
];

// Draw bars
$graph->draw_bars($data); // default green bars

// Render the graph as a PNG image
$graph->render();
```
This will output a PNG image displaying a bar graph of the provided data.

## Documentation 📄
### Class: Minibar
#### Methods
- `__construct(int $width = 1000, int $height = 500, int $margin = 50)` Initializes a new graph with specified dimensions and margin.
- `set_size(int $width, int $height)` Sets the size of the graph image.
- `set_background_color(int $r = 0, int $g = 0, int $b = 0)` Sets the background color using RGB values.
- `set_label(string $text, int $r = 255, int $g = 255, int $b = 255)` Places a centered label at the top of the graph.
- `draw_lines(array $data, string $hexColor = "#ff0000", bool $showValues = true, bool $showKeys = true)` Draws line graph, with options for showing values and keys.
- `draw_bars(array $data, string $hexColor = "#00ff00", bool $showValues = true, bool $showKeys = true)` Draws bars for each data point, with options for showing values and keys.
- `render()` Outputs the generated graph as a PNG image.

## Contributing 🤝
Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are greatly appreciated!

## License 📜
Distributed under the MIT License. See LICENSE for more information.
