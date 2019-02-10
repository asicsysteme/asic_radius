<div class="navbar  navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
    </div>
    
    <div class="collapse navbar-collapse">
     <form id="form" action="index.php" class="map_search form-inline"   method="post" >
      
      <input name="verif" type="hidden" value="<?php MInit::form_verif('form');?>" />
      <div class="form-group">
        <?php
        
    global $db;
        $sql = "SELECT  icone_id as id,icone_categorie as text , icone_icon as icon FROM map_markers_icone  order by icone_id  limit 0,1000 ";
      if (! $db->Query($sql)) $db->Kill($db->Error() .' '.$sql);
        ?>
        <label for="exampleInputEmail2">Technologie :</label>
        
        <select name="marker" class="form-control input-sm">
          <option value="all"   >  Tout</option>
           <?php while (! $db->EndOfSeek()) {
          $row = $db->Row();
          echo '<option value="'.$row->id.'"   >'.$row->text.'</option>';
           }

           ?>
        </select>
      </div>
      <div class="form-group">
         <?php
    global $db;
        $sql = "SELECT   id, ville as text  FROM ref_ville  order by ville  limit 0,1000 ";
      if (! $db->Query($sql)) $db->Kill($db->Error() .' '.$sql);
        ?>
        <label for="exampleInputEmail2">Ville :</label>
        
        <select name="ville" class="form-control input-sm">
          <option value=""   >  Tout</option>
           <?php while (! $db->EndOfSeek()) {
          $row = $db->Row();
          echo '<option value="'.$row->id.'"   >'.$row->text.'</option>';
           }

           ?>
        </select>
      </div>


      <button type="submit" class="btn btn-primary btn-sm">Envoyer</button>

<div class="form-group" style="margin-left:14px ;">

     <img src="common\images\marker-map\map-icon\fh.png" > <small style="color: #2d6ca2;"> &nbsp;
      Liaison F.H  &nbsp;
    </small>
     <img src="common\images\marker-map\map-icon\gsm.png" > <small style="color: #2d6ca2;">&nbsp; Site G.S.M&nbsp;  </small>
     <img src="common\images\marker-map\map-icon\uhf.png" > <small style="color: #2d6ca2;"> &nbsp;Station U.H.F &nbsp; </small>
     <img src="common\images\marker-map\map-icon\vhf.png" > <small style="color: #2d6ca2;"> &nbsp; Station V.H.F  &nbsp;</small>
     <img src="common\images\marker-map\map-icon\vsat.png" > <small style="color: #2d6ca2;"> &nbsp;Station V.S.A.T  &nbsp; </small>
       <img src="common\images\marker-map\map-icon\blr.png" > <small style="color: #2d6ca2;"> &nbsp;Station B.L.R   </small>


      </div>
    </form>

  </div><!--/.nav-collapse -->
</div>
</div>