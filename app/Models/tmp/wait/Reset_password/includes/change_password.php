

<form action="" method="post" onsubmit="return change_password_submit(this)" autocomplete="off">
    
    
    <input type="hidden" name="key"  value="<?php echo $_GET["key"];?>" readonly>

       
    <div class="header"><?php echo Sentence::translate("Shift password");?></div>
        
        
    <div class="content">
           
           
        <label>
            
            <?php echo Sentence::translate("New password");?>
                
            <input type="password" name="password"  value="" autocomplete="new-password" required="required">
                
        </label>
              
            
        <label>
            	
            <?php echo Sentence::translate("Repeat password");?>
                
            <input name="repeat_password" type="password" value="" autocomplete="new-password" required="required"> 
            
        </label>
            
            
        <button type="submit" ><?php echo Sentence::translate("Change password");?></button>
            
            
    </div>
        

</form>