<?php
if(! defined( 'ABSPATH' )) exit;


function is_hugeit_public_submission($submission_label){
    if(in_array(strtolower($submission_label),['reset','simple captcha','paypal'])){
        return false;
    }
    return true;
}

function hugeit_contact_front_end_submissions($submissionsArray,$view,$per_page,$fields=array())
{
    echo "bbbbb";

    //ob_start();

    echo "<table>";
    switch($view){
        case 'default'://default view,all submission info in one column
            echo "<tr>".'<th>ID</th> <th>Date</th> <th>Submission</th>'.'</tr>';
            foreach ($submissionsArray as $key=>$submission){
                ?>
                <tr class="hugeit_front_submission_row">
                    <td><?php echo $submission['id'];?></td>
                    <td><?php echo $submission['submission_date'];?></td>

                    <?php
                    $submission_text_final='';
                    $submission_texts=explode('*()*',$submission['submission']);
                    $submission_labels=explode('*()*',$submission['sub_labels']);

                    foreach ($submission_labels as $index=>$submission_label){
                        if(is_hugeit_public_submission($submission_label)){
                            if($submission_label && $submission_texts[$index]){
                                $submission_text_final.='<strong>'.$submission_label.'</strong>: '.$submission_texts[$index].'<br>';
                            }
                        }
                    }
                    ?>
                    <td><?php echo $submission_text_final;?></td>



                </tr>
                <?php
            }
            break;

        case '2'://each submission field in separate column
            echo "<tr>";
            foreach($fields as $key=>$field){
                if(is_hugeit_public_submission($field))echo "<th>".$field."</th>";
            }
            echo "</tr>";



            //var_dump($submissionsArray);
            //var_dump($fields);
            break;
    }

    echo "</table>";

    //ob_get_clean();
}
