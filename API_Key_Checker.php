<?php

function checkApi($conn,$api_key)
{
    $response = array();
    $query = "SELECT MU_ID,API_KEY FROM machine_unit";

    $result = mysqli_query($conn,$query);

    $counter = 0;
    $SELECTED_MU_ID = NULL;
    
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)){
            if ($api_key == $row['API_KEY']) {
                $counter++;
                $SELECTED_MU_ID = $row['MU_ID'];
            }
        }

        if ($counter == 1 && $SELECTED_MU_ID != NULL) {
            $response['MU_ID'] =$SELECTED_MU_ID;
            $response['Success'] = true;
        }else{
            $response['Success'] = false;
        }
    }

    return $response;
}




?>