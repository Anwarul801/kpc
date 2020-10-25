<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>


<div class="container">
  <h2>Configuration Update</h2>
  <form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
    <div class="form-group">
      <label for="Configuration">Field Name:</label>
      <input type="text" class="form-control" id="field" value="<?php echo $getSingleModel[0]->fieldName;?>" placeholder="Enter field" require name="field">
    </div>
    <div class="form-group">
      <label for="Percentance">Percentance:</label>
      <input type="text" class="form-control" id="percentance" value="<?php echo $getSingleModel[0]->percentance;?>" placeholder="Enter percentance" require name="percentance">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>
