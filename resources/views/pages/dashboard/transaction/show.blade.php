<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Transaction &raquo; {{$item->id}}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-lg text-gray-800 leading-tight mb-5">Room Chat Details</h2>

            <div class="bg-white overflow-hidden shadow sm:rounded-lg mb-10">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="table-auto w-full">
                        <tbody>
                            <tr>
                                <th class="border px-6 py-4 text-right">Nama User</th>
                                <td class="border px-6 py-4">{{ \App\Models\User::find($item->user_id)->name }}</td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-right">Email Klien</th>
                                <td class="border px-6 py-4">{{ \App\Models\User::find($item->user_id)->email }}</td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-right">Nama Konsultan</th>
                                <td class="border px-6 py-4">{{$upartner->name }}</td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-right">Services Konsultan</th>
                                <td class="border px-6 py-4">{{ \App\Models\CitraService::find($item->partner->services_id)->name }}</td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-right">Status</th>
                                <td class="border px-6 py-4">{{ $item->status }}</td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-right">Total Transaction</th>
                                <td class="border px-6 py-4">{{ $item->total }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>