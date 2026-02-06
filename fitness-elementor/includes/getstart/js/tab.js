function fitness_elementor_open_tab(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}

jQuery(document).ready(function () {
    jQuery( ".tab-sec .tablinks" ).first().addClass( "active" );
});

function fitness_elementor_copyCoupon() {
    const fitness_elementor_coupon = document.getElementById("coupon-code").innerText;
    const fitness_elementor_button = document.getElementById("copy-btn");
    const fitness_elementor_icon = fitness_elementor_button.querySelector("i");
  
    navigator.clipboard.writeText(fitness_elementor_coupon).then(() => {
        fitness_elementor_icon.classList.remove("fa-regular");
        fitness_elementor_icon.classList.add("fa-solid"); // change icon to indicate success
      setTimeout(() => {
        fitness_elementor_icon.classList.remove("fa-solid");
        fitness_elementor_icon.classList.add("fa-regular");
      }, 2000);
    });
  }
/* ---------------------------
   🔹 Load More / Load Less for Changelog
----------------------------*/
document.addEventListener('DOMContentLoaded', function() {
    const changelogBlocks = document.querySelectorAll('.block-changelog');
    const loadMoreBtn = document.getElementById('fitness-elementor-load-more');

    if (!changelogBlocks.length || !loadMoreBtn) return; 

    const initialVisible = 5; 
    let visibleCount = initialVisible;
    let expanded = false;

    changelogBlocks.forEach((block, index) => {
        if (index >= initialVisible) block.style.display = 'none';
    });

    loadMoreBtn.addEventListener('click', function() {
        if (!expanded) {
            for (let i = visibleCount; i < visibleCount + 5 && i < changelogBlocks.length; i++) {
                changelogBlocks[i].style.display = 'flex';
            }
            visibleCount += 5;

            if (visibleCount >= changelogBlocks.length) {
                expanded = true;
                loadMoreBtn.textContent = 'Load Less';
            }
        } else {
            changelogBlocks.forEach((block, index) => {
                block.style.display = index < initialVisible ? 'flex' : 'none';
            });
            visibleCount = initialVisible;
            expanded = false;
            loadMoreBtn.textContent = 'Load More';

            changelogBlocks[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});


