<?php
class FilterManager
{
    private $base_url;
    private $active_filters = [];
    private $all_filters;

    public function __construct($base_url, $all_filters)
    {
        $this->base_url = $base_url;
        $this->all_filters = $all_filters;
        $this->processActiveFilters();
    }

    private function processActiveFilters()
    {
        foreach ($_GET as $filter => $values) {
            $values = is_array($values) ? $values : [$values];
            $this->active_filters[$filter] = array_map(
                fn($v) => htmlspecialchars($v, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                $values
            );
        }
    }

    public function getActiveFilters()
    {
        return $this->active_filters;
    }

    private function buildQueryUrl($filters, $filter, $value)
    {
        $values = array_map('strval', $filters[$filter] ?? []);
        $string_value = (string) $value;
        $position = array_search($string_value, $values, TRUE);

        if ($position !== FALSE) {
            unset($values[$position]);
            $values = array_values($values);
        } else {
            $values[] = $string_value;
        }

        if (!empty($values)) {
            $filters[$filter] = $values;
        } else {
            unset($filters[$filter]);
        }

        return $this->base_url . '?' . http_build_query($filters);
    }

    public function renderFilters()
    {
        echo '<div class="filter-ui my-4 p-4 border rounded shadow-sm bg-light">';
        echo '<h3 class="h5 mb-3 text-secondary">Filter Street Foods</h3>';

        if (empty($this->all_filters)) {
            echo '<div class="alert alert-warning">Filter options are not available.</div>';
            return;
        }

        foreach ($this->all_filters as $filter => $options) {
            $heading = ucwords(str_replace("_", " ", $filter));
            echo '<h4 class="fw-bold mt-4 mb-2 small text-uppercase text-muted">' . $heading . '</h4>';

            echo '<div class="btn-group mb-3 flex-wrap" role="group" aria-label="' . $heading . ' Filter Group">';

            foreach ($options as $value => $label) {
                $is_active = in_array($value, $this->active_filters[$filter] ?? []);
                $url = $this->buildQueryUrl($_GET, $filter, $value);

                echo '<a href="' . $url . '" class="btn btn-sm ' . ($is_active ? 'btn-danger' : 'btn-outline-secondary') . ' me-1 mb-1" aria-pressed="' . ($is_active ? 'true' : 'false') . '">' . $label . '</a>';
            }

            echo '</div>';
        }
        echo '</div>'; // Close filter-ui
    }
}


$filterManager = new FilterManager($_SERVER['PHP_SELF'], $filters);
$filterManager->renderFilters();
?>