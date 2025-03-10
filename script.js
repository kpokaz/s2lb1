document.addEventListener('DOMContentLoaded', () => {
    const button = document.getElementById('button');
    const output_all = document.getElementById('output');

    button.addEventListener('click', () => {
        fetch('all_goods.php')
            .then(response => response.text())
            .then(data => {
                output_all.innerHTML = data;
            })
            .catch(error => {
                console.error('Error:', error);
                output_all.innerHTML = '<p style="color: red;">Data download error</p>';
            });
    });
});

document.getElementById("category-button").addEventListener("click", function(event) {
    event.preventDefault();

    let selectedCheckboxes = document.querySelectorAll('input[name="category-option"]:checked');
    let selectedValues = Array.from(selectedCheckboxes).map(cb => cb.value);

    if (selectedValues.length === 0) {
        alert("Choose at least 1 category!");
        return;
    }

    let queryString = selectedValues.map(category => `categories[]=${encodeURIComponent(category)}`).join("&");
    let url = `by_categories.php?${queryString}`;

    console.log("Send GET-query on:", url); // DEBUG

    fetch(url)
        .then(response => response.text()) 
        .then(data => {
            document.getElementById("output").innerHTML = data;
        })
        .catch(error => console.error("Error:", error));
});

document.getElementById("vendors-button").addEventListener("click", function(event) {
    event.preventDefault();

    let selectedCheckboxes = document.querySelectorAll('input[name="vendor-option"]:checked');
    let selectedValues = Array.from(selectedCheckboxes).map(cb => cb.value);

    if (selectedValues.length === 0) {
        alert("Choose at least 1 category!");
        return;
    }

    let queryString = selectedValues.map(vendor => `vendors[]=${encodeURIComponent(vendor)}`).join("&");
    let url = `by_vendors.php?${queryString}`;

    console.log("Send GET-query on:", url); 

    fetch(url)
        .then(response => response.text()) 
        .then(data => {
            document.getElementById("output").innerHTML = data;
        })
        .catch(error => console.error("Error:", error));
});

document.addEventListener("DOMContentLoaded", function () {
    let minPriceInput = document.getElementById("min-price");
    let maxPriceInput = document.getElementById("max-price");
    let minPriceValue = document.getElementById("min-price-value");
    let maxPriceValue = document.getElementById("max-price-value");

    
    minPriceInput.addEventListener("input", function () {
        minPriceValue.textContent = this.value;
    });

    maxPriceInput.addEventListener("input", function () {
        maxPriceValue.textContent = this.value;
    });

    document.getElementById("search-button").addEventListener("click", function () {
        let minPrice = minPriceInput.value;
        let maxPrice = maxPriceInput.value;

        fetch(`by_price_range.php?min_price=${minPrice}&max_price=${maxPrice}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById("output").innerHTML = data;
            })
            .catch(error => console.error("Download error:", error));
    });
});