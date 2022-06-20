<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Citra Partner') }}
        </h2>
    </x-slot>

    <x-slot name="script">
        <script>
            //Ajax DataTable
            var dataTable = $('#crudTable').DataTable({
                ajax: {
                    url: "{!! url()->current() !!}",
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: '5%'
                    },
                    {
                        data: 'user.name',
                        name: 'user',

                    }, {
                        data: 'service.name',
                        name: 'service',
                        width: '25%'
                    },
                    {
                        data: 'price',
                        name: 'price',
                        width: '5%'
                    }, {
                        data: "action",
                        name: "action",
                        orderable: "false",
                        searchable: "false",
                        width: '25%',
                    }
                ],
            });
        </script>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-10">
                <a href="{{ route('dashboard.partner.create') }}" class="px-4 py-2 font-bold text-white bg-green-500 rounded shadow-lg hover:bg-green-700">
                    + Create Citra Partner
                </a>
            </div>
            <div class="shadow-overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <table id="crudTable" class="w-full table-auto">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Konsultan</th>
                                <th>Service</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>