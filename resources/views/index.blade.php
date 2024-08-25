<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ExpansesCalculator</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

{{-- make container fill the screen --}}

<body class="h-screen bg-gray-800 flex ">
    <div class="my-24 mx-24 grid grid-cols-3 gap-8">

        {{-- Income --}}
        <div class="rounded-md bg-white p-6 border-b-8 border-green-600">
            <h1 class="text-xl font-bold">Income</h1>

            <div class="py-6 px-3">
                <h1 class="text-lg ">Rp. {{ $income }}</h1>

                {{-- pie chart --}}
                <div class="my-6">
                    <canvas id="incomePieChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Calculate --}}
        <div class="grid gap-2 rounded-md bg-white p-6">
            <div class="flex flex-col text-center">
                <h1 class="text-xl font-bold">Calculate</h1>
                <p>Track your income and expanse</p>
            </div>

            <div class="border-b border-gray-400">
                <h1 class="text-xl text-center">Total Balance: Rp. {{ $balance }}</h1>
            </div>

            {{-- calculate input --}}
            <form action="/" method="POST" class="flex flex-col">
                @csrf
                <div class="grid grid-cols-2 gap-2 my-4">

                    {{-- type --}}
                    <div class="flex flex-col">
                        <label>Type</label>
                        <select name="type_id" id="type" class="w-full rounded-lg border border-gray-300 p-2">
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}" class="capitalize">
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- category --}}
                    <div class="flex flex-col">
                        <label>Category</label>
                        <select name="category_id" id="category" class="w-full rounded-lg border border-gray-300 p-2">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" data-type-id="{{ $category->type_id }}"
                                    class="capitalize">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- amount --}}
                    <x-input name="amount" type="number">Amount</x-input>

                    {{-- date --}}
                    <x-input name="date" type="date">Date</x-input>
                </div>

                <button class="rounded-lg bg-green-600 text-white text-base font-bold  py-3 px-6 text-center"
                    type="submit">
                    Create
                </button>
            </form>

            {{-- history list --}}
            <div class="h-48 overflow-auto">
                @foreach ($transactions as $transaction)
                    <div class="flex justify-between border-b border-gray-300 my-2 gap-6 py-2 px-4">
                        <div
                            class="{{ $transaction->type_id == 1 ? 'bg-green-600' : 'bg-red-600' }} h-14 w-14 rounded-full p-3">
                            <img src="images/rupiah-logo.svg" alt="rp" class="">
                        </div>
                        <div class="flex flex-col">
                            <p class="text-lg capitalize"> {{ $transaction->category->name }}</p>
                            <p> Rp. {{ $transaction->amount }} | {{ $transaction->date }}</p>
                        </div>

                        {{-- delete button --}}
                        <div class="h-full">
                            <form action="/{{ $transaction->id }}" method="POST" class="items-center">
                                @method('delete')
                                @csrf
                                <button class="p-2 text-center" type="submit"
                                    onclick="return confirm('Are you sure?')">
                                    <i class="material-icons text-red-600">delete</i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Expanse --}}
        <div class="rounded-md bg-white p-6 border-b-8 border-red-600">
            <h1 class="text-xl font-bold">Expanse</h1>

            <div class="py-6 px-3">
                <h1 class="text-lg ">Rp. {{ $expanse }}</h1>

                {{-- pie chart --}}
                <div class="my-6">
                    <canvas id="expansePieChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        var ctx = document.getElementById('incomePieChart').getContext('2d');

        var incomePieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($income_labels), // Using the labels passed from the controller
                datasets: [{
                    data: @json($income_amounts), // Using the data passed from the controller
                    // green color variant
                    backgroundColor: [
                        '#008a2eff',
                        '#00d612',
                        '#006809',

                    ],
                    borderColor: '#FFFFFF',

                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Income Transaction Distribution by Category'
                    }
                }
            }
        });

        var ctx = document.getElementById('expansePieChart').getContext('2d');
        var expansePieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($expanse_labels), // Using the labels passed from the controller
                datasets: [{
                    data: @json($expanse_amounts), // Using the data passed from the controller
                    backgroundColor: [
                        '#d600a1',
                        '#f16d00',
                        '#ffae00',

                    ],
                    borderColor: '#FFFFFF',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Expanse Transaction Distribution by Category'
                    }
                }
            }
        });

        const typeSelect = document.getElementById('type');
        const categorySelect = document.getElementById('category');
        const categoryOptions = categorySelect.querySelectorAll('option');

        console.log(typeSelect.value);


        function filterCategories() {
            const selectedTypeId = typeSelect.value;

            categoryOptions.forEach(option => {
                if (option.getAttribute('data-type-id') === selectedTypeId) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });

            // Reset the selected category when the type changes
            categorySelect.value = '';
        }

        filterCategories();
        typeSelect.addEventListener('change', filterCategories);
    </script>
</body>

</html>
