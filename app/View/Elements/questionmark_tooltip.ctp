<div id="questionmark_tooltip" onclick="showTooltip(<?=$id?>)"
     style="position:relative;cursor:pointer;margin-right: 10px;width: 22px; height: 22px; background: var(--off-white); border-radius: 22px; display:flex; align-items: center; justify-content: center; z-index:10">
    <svg xmlns="http://www.w3.org/2000/svg" width="6" height="10" viewBox="0 0 6 10">
        <g fill="none" fill-rule="evenodd">
            <g>
                <g>
                    <g>
                        <g>
                            <g transform="translate(-611 -387) translate(256 378) translate(347 3) translate(8 6) translate(.714 1.25)">
                                <path stroke="#041F74" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M1.786 5v-.875c0-.375 1.1-.917 1.443-1.192.386-.308.628-.752.628-1.245C3.857.755 2.994 0 1.93 0 .863 0 0 .756 0 1.688"/>
                                <ellipse cx="1.786" cy="7.188" fill="#041F74" rx="1.071" ry="1"/>
                            </g>
                        </g>
                    </g>
                </g>
            </g>
        </g>
    </svg>
    <style>
        #tooltip_div<?=$id?>:before {
            content: '';
            display: inline-block;
            width: 1rem;
            height: 1rem;
            background-color: var(--off-white);
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%)rotate(45deg);
        }
    </style>

    <div id="tooltip_div<?=$id?>" style="   display: none;
                                    position: absolute;
                                    top: 2.5rem;
                                    border-radius: 10px;
                                    width: 392px;
                                    height: 150px;
                                    padding: 1rem 1.5rem;
                                    box-shadow: 0 1px 6px 0 rgba(77, 87, 143, 0.4);
                                    background-color: var(--off-white);
                                    cursor: default;
                                  "
    >
        <p style="margin-top: .5rem">Let op! Als browsertoetsen aanstaat, dan kan de student de toets maken zonder de beveiligde studenten app. In de browser kunnen wij het gebruik van andere apps niet blokkeren. </p>
        <a href="https://support.test-correct.nl/knowledge/inbrowser-toetsen" target="_blank" class="text-button" style="text-decoration: none"><span style="margin-right: 10px">Lees meer op de Kennisbank</span><?php echo $this->element('arrow') ?></a>
    </div>
    <script>
        function showTooltip(id) {
            var tip = document.getElementById('tooltip_div'+id);
            tip.style.display === 'none' ? tip.style.display = 'block' : tip.style.display = 'none';
        }
    </script>
</div>
