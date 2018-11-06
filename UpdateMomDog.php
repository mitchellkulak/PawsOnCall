    else{
        if ($db->query(
            "UPDATE Dogs 
            SET(ID = $dog_id, Name = '$dog_name', VolunteerID = $vol_id, Sex = '$sex', 
            Birthdate = $birth_date, Adoptiondate = $adopt_date, Deathdate = $death_date, Breed = '$breed', LitterID = $litter_id)") === TRUE) {
                echo "Record updated successfully";
            } else {
                echo "Error updating record: " . $db->error;
            }
    }