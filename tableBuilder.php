<?php

  //wrapper for add_row, for easy user interface
  function add_head_row($row){
    echo add_row($row, 'thead');
  }

  //wrapper for add_row, for easy user interface
  function add_body_row($row){
    echo add_row($row, 'tbody');
  }

  //takes in a single 1D row array, optional row type, body by default
  function add_row($row, $type = 'tbody'){
    echo "<tr>" .
     array_reduce($row, function($persist, $index){
        $t = $type == 'thead' ? ['<th>', '</th>'] : ['<td>', '</td>'];
        return $persist.$t[0].$index.$t[1];
    }, '') .
    "</tr>";
  }

  //takes in a 2D array of rows, optional row type, body by default
  function get_row_group($rows, $type='tbody'){
    echo array_reduce($rows, function($persist, $index){
      return $persist.add_row($index, $type);
    }, '');
  }

?>
