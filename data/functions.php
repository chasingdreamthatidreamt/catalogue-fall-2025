<?php

function execute_prepared_statement($query, $params = [], $types = "") {
    global $connection;
    $statement = $connection->prepare($query);
    if (!$statement) {
        die("Preparation failed: " . $connection->error);
    }
    if (!empty($params)) {
        $statement->bind_param($types, ...$params);
    }
    if (!$statement->execute()) {
        die("Execution failed: " . $statement->error);
    }
    if (str_starts_with(strtoupper($query), "SELECT")) {
        return $statement->get_result();
    }
    return TRUE;
}

function get_all_cities() {
    $query = "SELECT * FROM cities;";
    $result = execute_prepared_statement($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function generate_table() {
    $cities = get_all_cities();
    if (count($cities) > 0) {
        echo "<table class=\"table table-bordered table-hover\"> 
              <thead> 
              <tr class=\"table-dark\"> 
              <th scope=\"col\">City Name</th> 
              <th scope=\"col\">Population</th> 
              <th scope=\"col\">Trivia</th> 
              </tr> 
              </thead> 
              <tbody> ";

        foreach ($cities as $city) {
            extract($city);
            $capital = ($is_capital) ? '&starf;' : '';
            $trivia_info = ($trivia != NULL) ? "<i class=\"bi bi-info-circle\" data-bs-toggle=\"tooltip\" title=\"$trivia\"></i>" : '';
            $population = number_format($population);
            echo "<tr> 
                  <td>$capital $city_name, $province</td> 
                  <td>$population</td> 
                  <td>$trivia_info</td> 
                  </tr>";
        }

        echo "</tbody> 
              </table> 
              <aside> 
              <h3 class=\"fs-5 fw-normal\">Notes</h3> 
              <p class=\"text-muted my-3\">&starf; indicates the capital of a province or territory.</p> 
              <p class=\"text-muted my-3\">Hover over <i class=\"bi bi-info-circle\" data-bs-toggle=\"tooltip\" title=\"I'm a tooltip!\"></i> to see additional trivia about the city.</p> 
              </aside>";
    } else {
        echo "<h2 class=\"fw-light\">Oh no!</h2>";
        echo "<p>We're sorry, but we weren't able to find anything.</p>";
    }
}
?>
