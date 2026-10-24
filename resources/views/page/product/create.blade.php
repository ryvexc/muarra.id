@extends("layout.admin.main")

@section("content")
    <section>
        <form method="POST" action="{{ route('product.post') }}" id="product-form">
            @csrf
            <div class="page-header">
                <div>
                    <h1>New Product</h1>
                    <p>Add a new product and define its recipe ingredients.</p>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Save Product
                </button>
            </div>

            <div class="form-card">
                <div class="form-card-header">
                    <h2>Product Information</h2>
                    <p>Basic information about the product.</p>
                </div>

                <div class="form-grid">
                    <div class="form-group no-margin">
                        <label>Product Name</label>
                        <input
                            type="text"
                            name="name"
                            placeholder="Example: Iced Coffee"
                            required>
                    </div>

                    <div class="form-group no-margin">
                        <label>Category</label>

                        <select name="product_category_id" required>
                            <option value="" selected disabled>
                                -- Select Category --
                            </option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->normalizedId() }} -
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group no-margin">
                        <label>Subcategory</label>

                        <select name="product_subcategory_id">
                            <option value="" selected disabled>
                                -- Select Subcategory --
                            </option>

                            @foreach ($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}">
                                    {{ $subcategory->normalizedId() }} -
                                    {{ $subcategory->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group no-margin">
                        <label>Harga Jual</label>
                        <input
                            type="text"
                            inputmode="numeric"
                            class="currency-input"
                            name="harga_jual"
                            placeholder="Example: 15000"
                            required>
                    </div>

                    {{-- Harga Modal --}}
                    <div class="form-group no-margin">
                        <label>Harga Modal</label>
                        <input
                            type="text"
                            inputmode="numeric"
                            class="currency-input"
                            name="harga_modal"
                            placeholder="Example: 8000"
                            required>

                        <small
                            id="hargaModalWarning"
                            style="
                                display: none;
                                color: #fbbf24;
                                font-size: 12px;
                                margin-top: 5px;
                            "
                            >
                            Harga modal tidak boleh kurang dari ingredient cost.
                        </small>
                    </div>
                </div>
            </div>

            {{-- Ingredients --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div>
                        <h2>Ingredients</h2>
                        <p>Define the ingredients used to produce this product.</p>
                    </div>
                </div>


                {{-- Add Ingredient --}}
                <div class="">
                    <div class="form-group">
                        <label>Item</label>

                        <select id="ingredientSelect">
                            <option value="" selected disabled>
                                -- Select Ingredient --
                            </option>

                            @foreach ($ingredients as $ingredient)
                                <option
                                    value="{{ $ingredient->id }}"
                                    data-id="{{ $ingredient->normalizedId() }}"
                                    data-name="{{ $ingredient->name }}"
                                    data-unit="{{ $ingredient->unit_of_measurement->symbol }}"
                                    data-unit-price="{{ $ingredient->harga_satuan }}"
                                >
                                    #{{ $ingredient->normalizedId() }} - {{ $ingredient->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Quantity</label>

                        <input
                            type="number"
                            id="ingredientQuantity"
                            min="0"
                            step="any"
                            placeholder="Example: 10">
                    </div>

                    <div class="ingredient-add-button">
                        <button
                            type="button"
                            class="btn-primary"
                            id="addIngredientButton">

                            <i class="fa-solid fa-plus"></i>
                            Add Ingredient
                        </button>
                    </div>
                </div>

                {{-- Used Ingredients --}}
                <div class="used-ingredients">
                    <div class="used-ingredients-header">
                        <div>
                            <h3>Used Ingredients</h3>
                            <p>Ingredients included in this product recipe.</p>
                        </div>
                    </div>

                    <div class="table-wrap ingredient-table">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 0">ITEM ID</th>
                                    <th style="width: 0">NAME</th>
                                    <th style="width: 0">SATUAN</th>
                                    <th style="width: 0">HARGA SATUAN</th>
                                    <th style="width: 0; text-align: left">QUANTITY</th>
                                    <th style="">COST</th>
                                    <th style="width: 0; text-align: right">ACTION</th>
                                </tr>
                            </thead>

                            <tbody id="ingredientTableBody">
                                <tr id="emptyIngredientRow">
                                    <td colspan="99999" style="text-align: center">
                                        <span class="date-cell">
                                            No ingredients added yet.
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Pricing Summary --}}
            <div class="form-card">
                <div class="form-card-header">
                    <h2>Pricing Summary</h2>
                    <p>Product cost and estimated profit.</p>
                </div>

                <div class="summary-grid" style="display: flex; flex-direction: column; gap: 12px;">
                    <div class="summary-item">
                        <span>Ingredient Cost</span>
                        <strong id="ingredientCostSummary">
                            Rp0
                        </strong>
                    </div>

                    <div class="summary-item">
                        <span>Harga Modal</span>
                        <strong id="hargaModalSummary">
                            Rp0
                        </strong>
                    </div>

                    <div class="summary-item">
                        <span>Harga Jual</span>
                        <strong id="hargaJualSummary">
                            Rp0
                        </strong>
                    </div>

                    <div class="summary-item">
                        <span>Estimated Profit</span>
                        <strong id="estimatedProfitSummary" style="color:#4ade80">
                            Rp0
                        </strong>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection

@section("script")
    <script>
        document.querySelectorAll('.currency-input').forEach(input => {
            input.addEventListener('input', function () {
                let raw = this.value.replace(/\D/g, '');

                this.value = raw
                    ? new Intl.NumberFormat('id-ID').format(Number(raw))
                    : '';

                // Cari hidden input yang namanya *_raw
                const rawInput = document.getElementById(`${this.id}_raw`);

                if (rawInput) {
                    rawInput.value = raw;
                }
            });
        });
    </script>

    {{-- <script>
        let ingredientIndex = 0;

        const ingredientSelect = document.getElementById("ingredientSelect");
        const ingredientQuantity = document.getElementById("ingredientQuantity");
        const addIngredientButton = document.getElementById("addIngredientButton");
        const ingredientTableBody = document.getElementById("ingredientTableBody");
        const emptyIngredientRow = document.getElementById("emptyIngredientRow");

        addIngredientButton.addEventListener("click", function () {

            const selectedOption =
                ingredientSelect.options[ingredientSelect.selectedIndex];

            const ingredientId = ingredientSelect.value;
            const quantity = parseFloat(ingredientQuantity.value);

            if (!ingredientId) {
                alert("Please select an ingredient.");
                return;
            }

            if (!quantity || quantity <= 0) {
                alert("Please enter a valid quantity.");
                return;
            }

            const id = selectedOption.dataset.id;
            const name = selectedOption.dataset.name;
            const unit = selectedOption.dataset.unit;
            const unitPrice = parseFloat(selectedOption.dataset.unitPrice || 0);

            const existingRow = ingredientTableBody.querySelector(
                `tr[data-ingredient-id="${ingredientId}"]`
            );

            if (existingRow) {
                alert("This ingredient has already been added.");
                return;
            }

            if (emptyIngredientRow) {
                emptyIngredientRow.remove();
            }

            const cost = quantity * unitPrice;

            const row = document.createElement("tr");

            row.dataset.ingredientId = ingredientId;

            row.innerHTML = `
                <td>
                    <strong>#${id}</strong>

                    <input
                        type="hidden"
                        name="ingredients[${ingredientIndex}][ingredient_id]"
                        value="${ingredientId}"
                    >
                </td>

                <td>
                    ${name}
                </td>

                <td>
                    ${unit}
                </td>

                <td style="text-align: left">
                    Rp${new Intl.NumberFormat("id-ID").format(unitPrice)}
                </td>

                <td style="text-align: left">
                    <input
                        type="number"
                        class="ingredient-quantity"
                        value="${quantity}"
                        min="0"
                        step="any"
                        data-unit-price="${unitPrice}"
                        name="ingredients[${ingredientIndex}][quantity]"
                    >
                </td>

                <td class="ingredient-cost" style="text-align: left">
                    Rp${new Intl.NumberFormat("id-ID").format(cost)}
                </td>

                <td>
                    <div style="display: flex; justify-content: flex-end;">
                        <button
                            type="button"
                            class="btn-danger remove-ingredient"
                            title="Remove"
                        >
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;

            ingredientTableBody.appendChild(row);

            ingredientIndex++;

            ingredientSelect.value = "";
            ingredientQuantity.value = "";

            updateIngredientIndexes();
        });

        ingredientTableBody.addEventListener("input", function (event) {

            if (!event.target.classList.contains("ingredient-quantity")) {
                return;
            }

            const input = event.target;
            const row = input.closest("tr");

            const quantity = parseFloat(input.value) || 0;
            const unitPrice = parseFloat(input.dataset.unitPrice) || 0;

            const cost = quantity * unitPrice;

            const costCell = row.querySelector(".ingredient-cost");

            costCell.textContent =
                `Rp${new Intl.NumberFormat("id-ID").format(cost)}`;
        });


        ingredientTableBody.addEventListener("click", function (event) {

            const button = event.target.closest(".remove-ingredient");

            if (!button) {
                return;
            }

            const row = button.closest("tr");

            row.remove();

            updateIngredientIndexes();

            if (!ingredientTableBody.querySelector("tr[data-ingredient-id]")) {

                ingredientTableBody.innerHTML = `
                    <tr id="emptyIngredientRow">
                        <td colspan="7" style="text-align: center">
                            <span class="date-cell">
                                No ingredients added yet.
                            </span>
                        </td>
                    </tr>
                `;
            }
        });


        function updateIngredientIndexes() {

            const rows = ingredientTableBody.querySelectorAll(
                "tr[data-ingredient-id]"
            );

            rows.forEach((row, index) => {

                const ingredientInput = row.querySelector(
                    'input[name*="[ingredient_id]"]'
                );

                const quantityInput = row.querySelector(
                    'input[name*="[quantity]"]'
                );

                ingredientInput.name =
                    `ingredients[${index}][ingredient_id]`;

                quantityInput.name =
                    `ingredients[${index}][quantity]`;
            });

            ingredientIndex = rows.length;
        }
    </script> --}}

    <script>
    let ingredientIndex = 0;

    const ingredientSelect = document.getElementById("ingredientSelect");
    const ingredientQuantity = document.getElementById("ingredientQuantity");
    const addIngredientButton = document.getElementById("addIngredientButton");
    const ingredientTableBody = document.getElementById("ingredientTableBody");
    const emptyIngredientRow = document.getElementById("emptyIngredientRow");
    const hargaModalWarning = document.getElementById("hargaModalWarning");

    const hargaModalInput = document.querySelector(
        'input[name="harga_modal"]'
    );

    const hargaJualInput = document.querySelector(
        'input[name="harga_jual"]'
    );

    const ingredientCostSummary = document.getElementById(
        "ingredientCostSummary"
    );

    const hargaModalSummary = document.getElementById(
        "hargaModalSummary"
    );

    const hargaJualSummary = document.getElementById(
        "hargaJualSummary"
    );

    const estimatedProfitSummary = document.getElementById(
        "estimatedProfitSummary"
    );


    function formatCurrency(value) {
        return `Rp${new Intl.NumberFormat("id-ID").format(value)}`;
    }


    function parseCurrency(value) {
        return Number(
            String(value).replace(/\D/g, "")
        ) || 0;
    }

    function validateHargaModal() {
        const ingredientCost = calculateIngredientCost();
        const hargaModal = parseCurrency(hargaModalInput.value);

        if (hargaModal > 0 && hargaModal < ingredientCost) {
            hargaModalWarning.style.display = "block";
        } else {
            hargaModalWarning.style.display = "none";
        }
    }

    function calculateIngredientCost() {
        let ingredientCost = 0;

        const rows = ingredientTableBody.querySelectorAll(
            "tr[data-ingredient-id]"
        );

        rows.forEach(row => {
            const quantityInput = row.querySelector(".ingredient-quantity");

            if (!quantityInput) return;

            const quantity = parseFloat(quantityInput.value) || 0;
            const unitPrice = parseFloat(quantityInput.dataset.unitPrice) || 0;

            ingredientCost += quantity * unitPrice;
        });

        return ingredientCost;
    }


    function updatePricingSummary() {
        const ingredientCost = calculateIngredientCost();

        const hargaModal = parseCurrency(hargaModalInput.value);
        const hargaJual = parseCurrency(hargaJualInput.value);

        const estimatedProfit = hargaJual - hargaModal;

        ingredientCostSummary.textContent =
            formatCurrency(ingredientCost);

        hargaModalSummary.textContent =
            formatCurrency(hargaModal);

        hargaJualSummary.textContent =
            formatCurrency(hargaJual);

        estimatedProfitSummary.textContent =
            formatCurrency(estimatedProfit);

        estimatedProfitSummary.style.color =
            estimatedProfit < 0
                ? "#f87171"
                : "#4ade80";

        validateHargaModal();
    }

    hargaModalInput.addEventListener("input", function () {
        updatePricingSummary();
    });

    hargaModalInput.addEventListener("blur", function () {
        const ingredientCost = calculateIngredientCost();
        const hargaModal = parseCurrency(this.value);

        if (hargaModal > 0 && hargaModal < ingredientCost) {
            this.value = new Intl.NumberFormat("id-ID")
                .format(ingredientCost);
        }

        updatePricingSummary();
    });

    /*
    |--------------------------------------------------------------------------
    | Add Ingredient
    |--------------------------------------------------------------------------
    */

    addIngredientButton.addEventListener("click", function () {

        const selectedOption =
            ingredientSelect.options[
                ingredientSelect.selectedIndex
            ];

        const ingredientId =
            ingredientSelect.value;

        const quantity =
            parseFloat(ingredientQuantity.value);


        if (!ingredientId) {
            alert("Please select an ingredient.");
            return;
        }


        if (!quantity || quantity <= 0) {
            alert("Please enter a valid quantity.");
            return;
        }


        const id =
            selectedOption.dataset.id;

        const name =
            selectedOption.dataset.name;

        const unit =
            selectedOption.dataset.unit;

        const unitPrice =
            parseFloat(
                selectedOption.dataset.unitPrice || 0
            );


        // Prevent duplicate ingredient
        const existingRow =
            ingredientTableBody.querySelector(
                `tr[data-ingredient-id="${ingredientId}"]`
            );


        if (existingRow) {
            alert("This ingredient has already been added.");
            return;
        }


        // Remove empty state
        if (emptyIngredientRow) {
            emptyIngredientRow.remove();
        }


        const cost =
            quantity * unitPrice;


        const row =
            document.createElement("tr");

        row.dataset.ingredientId =
            ingredientId;


        row.innerHTML = `
            <td>
                <strong>#${id}</strong>

                <input
                    type="hidden"
                    name="ingredients[${ingredientIndex}][ingredient_id]"
                    value="${ingredientId}"
                >
            </td>

            <td>
                ${name}
            </td>

            <td>
                ${unit}
            </td>

            <td style="text-align: left">
                ${formatCurrency(unitPrice)}
            </td>

            <td style="text-align: left">
                <input
                    type="number"
                    class="ingredient-quantity"
                    value="${quantity}"
                    min="0"
                    step="any"
                    data-unit-price="${unitPrice}"
                    name="ingredients[${ingredientIndex}][quantity]"
                >
            </td>

            <td
                class="ingredient-cost"
                style="text-align: left"
            >
                ${formatCurrency(cost)}
            </td>

            <td>
                <div style="display: flex; justify-content: flex-end;">
                    <button
                        type="button"
                        class="btn-danger remove-ingredient"
                        title="Remove"
                    >
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </td>
        `;


        ingredientTableBody.appendChild(row);

        ingredientIndex++;


        // Reset add form
        ingredientSelect.value = "";
        ingredientQuantity.value = "";


        updateIngredientIndexes();

        // IMPORTANT
        updatePricingSummary();
    });


    /*
    |--------------------------------------------------------------------------
    | Edit Quantity
    |--------------------------------------------------------------------------
    */

    ingredientTableBody.addEventListener(
        "input",
        function (event) {

            if (
                !event.target.classList.contains(
                    "ingredient-quantity"
                )
            ) {
                return;
            }


            const input =
                event.target;

            const row =
                input.closest("tr");


            const quantity =
                parseFloat(input.value) || 0;

            const unitPrice =
                parseFloat(
                    input.dataset.unitPrice
                ) || 0;


            const cost =
                quantity * unitPrice;


            const costCell =
                row.querySelector(
                    ".ingredient-cost"
                );


            costCell.textContent =
                formatCurrency(cost);


            updatePricingSummary();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Remove Ingredient
    |--------------------------------------------------------------------------
    */

    ingredientTableBody.addEventListener(
        "click",
        function (event) {

            const button =
                event.target.closest(
                    ".remove-ingredient"
                );


            if (!button) {
                return;
            }


            const row =
                button.closest("tr");


            row.remove();


            updateIngredientIndexes();

            updatePricingSummary();


            // Show empty state
            if (
                !ingredientTableBody.querySelector(
                    "tr[data-ingredient-id]"
                )
            ) {

                ingredientTableBody.innerHTML = `
                    <tr id="emptyIngredientRow">
                        <td
                            colspan="7"
                            style="text-align: center"
                        >
                            <span class="date-cell">
                                No ingredients added yet.
                            </span>
                        </td>
                    </tr>
                `;
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Harga Modal / Harga Jual
    |--------------------------------------------------------------------------
    */

    hargaModalInput.addEventListener(
        "input",
        updatePricingSummary
    );

    hargaJualInput.addEventListener(
        "input",
        updatePricingSummary
    );


    /*
    |--------------------------------------------------------------------------
    | Re-index ingredients
    |--------------------------------------------------------------------------
    */

    function updateIngredientIndexes() {

        const rows =
            ingredientTableBody.querySelectorAll(
                "tr[data-ingredient-id]"
            );


        rows.forEach((row, index) => {

            const ingredientInput =
                row.querySelector(
                    'input[name*="[ingredient_id]"]'
                );

            const quantityInput =
                row.querySelector(
                    'input[name*="[quantity]"]'
                );


            ingredientInput.name =
                `ingredients[${index}][ingredient_id]`;

            quantityInput.name =
                `ingredients[${index}][quantity]`;
        });


        ingredientIndex =
            rows.length;
    }


    // Initial state
    updatePricingSummary();
</script>
@endsection