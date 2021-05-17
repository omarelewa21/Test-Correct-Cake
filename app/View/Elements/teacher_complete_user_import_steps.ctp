<?php if ($step == 1) { ?>
    <div style="display: flex; align-items: center; justify-content:center; margin-right: 24px">
        <div style="width: 30px; height: 30px; border-radius: 100%; background-color: var(--primary); color: white; display:flex; flex-grow:1; justify-content: center; align-items:center">
            <span>1</span>
        </div>
        <span style="margin-left: 10px; color: var(--primary)">Niveau stamklassen</span>
    </div>
    <div style="display: flex; align-items: center; justify-content:center; margin-right: 24px; color: var(--mid-grey)">
        <div style="width: 30px; height: 30px; border-radius: 100%; background-color: var(--mid-grey); color: white; display:flex; flex-grow:1; justify-content: center; align-items:center">
            <span>2</span>
        </div>
        <span style="margin-left: 10px;">Niveau &amp; leerjaar klusterklassen</span>
    </div>
    <div style="display: flex; align-items: center; justify-content:center;color: var(--mid-grey)">
        <div style="width: 30px; height: 30px; border-radius: 100%; background-color: var(--mid-grey); color: white; display:flex; flex-grow:1; justify-content: center; align-items:center">
            <span>3</span>
        </div>
        <span style="margin-left: 10px;">Vak stam- &amp; klusterklassen</span>
    </div>
<?php } ?>

<?php if ($step == 2) { ?>
    <div style="display: flex; align-items: center; justify-content:center; margin-right: 24px">
        <div style="width: 30px; height: 30px; border-radius: 100%; background-color: var(--primary); color: white; display:flex; flex-grow:1; justify-content: center; align-items:center">
            <?php echo $this->element('checkmark'); ?>
        </div>
        <span style="margin-left: 10px; color: var(--primary)">Niveau stamklassen</span>
    </div>
    <div style="display: flex; align-items: center; justify-content:center; margin-right: 24px">
        <div style="width: 30px; height: 30px; border-radius: 100%; background-color: var(--primary); color: white; display:flex; flex-grow:1; justify-content: center; align-items:center">
            <span>2</span>
        </div>
        <span style="margin-left: 10px; color: var(--primary)">Niveau &amp; leerjaar klusterklassen</span>
    </div>
    <div style="display: flex; align-items: center; justify-content:center">
        <div style="width: 30px; height: 30px; border-radius: 100%; background-color: var(--mid-grey); color: white; display:flex; flex-grow:1; justify-content: center; align-items:center">
            <span>3</span>
        </div>
        <span style="margin-left: 10px; color: var(--mid-grey)">Vak stam- &amp; klusterklassen</span>
    </div>
<?php } ?>

<?php if ($step == 3) { ?>
    <div style="display: flex; align-items: center; justify-content:center; margin-right: 24px">
        <div style="width: 30px; height: 30px; border-radius: 100%; background-color: var(--primary); color: white; display:flex; flex-grow:1; justify-content: center; align-items:center">
            <?php echo $this->element('checkmark'); ?>
        </div>
        <span style="margin-left: 10px; color: var(--primary)">Niveau stamklassen</span>
    </div>
    <div style="display: flex; align-items: center; justify-content:center; margin-right: 24px">
        <div style="width: 30px; height: 30px; border-radius: 100%; background-color: var(--primary); color: white; display:flex; flex-grow:1; justify-content: center; align-items:center">
            <?php echo $this->element('checkmark'); ?>
        </div>
        <span style="margin-left: 10px; color: var(--primary)">Niveau &amp; leerjaar klusterklassen</span>
    </div>
    <div style="display: flex; align-items: center; justify-content:center">
        <div style="width: 30px; height: 30px; border-radius: 100%; background-color: var(--primary); color: white; display:flex; flex-grow:1; justify-content: center; align-items:center">
            <span>3</span>
        </div>
        <span style="margin-left: 10px; color: var(--primary)">Vak stam- &amp; klusterklassen</span>
    </div>
<?php } ?>
