<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Room Chat') }}
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

                    },
                    {
                        data: 'partners_id',
                        name: 'partners_id',
                        width: '5%'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        width: '10%'

                    },
                    {
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
                <a href="{{ route('dashboard.room.create') }}" class="px-4 py-2 font-bold text-white bg-green-500 rounded shadow-lg hover:bg-green-700">
                    + Create Room Chat
                </a>
            </div>
            <div class="shadow-overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <table id="crudTable" class="w-full table-auto">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Klien</th>
                                <th>ID Konsultan</th>
                                <th>Status</th>
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