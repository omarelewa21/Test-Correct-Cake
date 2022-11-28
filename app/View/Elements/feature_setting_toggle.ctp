<label class="switch" style="display:flex;">
    <?= $this->Form->input($attribute, [
        'checked' => isset($source) && isset($source[$attribute]) && $source[$attribute],
        'label'   => false,
        'type'    => 'checkbox',
        'value'   => (isset($source) && isset($source[$attribute]) && $source[$attribute]) ? 1 : 0,
        'div'     => false,
        'style'   => 'width:20px;',
        'onclick' => "$clickAction(this.checked, '$attribute')",
    ]) ?>
    <span class="slider round"></span>
</label>