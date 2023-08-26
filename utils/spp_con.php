<?php

$sql = "SELECT * FROM spend_product";

$query = $conn->query($sql);


$data_list = array();

while ($data = mysqli_fetch_array($query)) {

    $spp_id = $data['spp_id'];

    $spp_name = $data['spp_name'];

    $data_list[$id] = $spp_name;
}
