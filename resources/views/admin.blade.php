@push('styles')
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/quasar@1.15.10/dist/quasar.min.css" rel="stylesheet" type="text/css">
@endpush
<script>
    var users = @json($users);;
</script>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @verbatim
                        <div id="q-app">
                            <template>
                                <div class="q-pa-md">
                                    <q-table
                                    title="Treats"
                                    :data="users"
                                    :columns="userColumns"
                                    row-key="name"
                                    binary-state-sort
                                    >
                                        <template v-slot:body="props">
                                            <q-tr :props="props">
                                                <q-td key="name" :props="props">
                                                    {{ props.row.name }}
                                                </q-td>
                                                <q-td key="email" :props="props">
                                                    {{ props.row.email }}
                                                </q-td>
                                                <q-td key="approved" :props="props">
                                                    <div class="text-pre-wrap">{{ approvedValue(props.row.approved) }}</div>
                                                    <q-popup-edit 
                                                        v-model="props.row.approved" 
                                                        buttons
                                                        @cancel="(val, initval) => updateApprovalValue(props.row, initval)"
                                                        @save="(val, initval) => updateApprovalValue(props.row, val)"
                                                    >
                                                        <q-toggle
                                                            :label="`Approved`"
                                                            :value="approvedValue(props.row.approved)"
                                                            @input="(val, evt) => updateUserApproval(props.row, val, evt)"
                                                        />
                                                    </q-popup-edit>
                                                </q-td>
                                            </q-tr>
                                        </template>
                                    </q-table>
                                </div>
                            </template>
                        </div>
                    @endverbatim
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/vue@^2.0.0/dist/vue.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quasar@1.15.10/dist/quasar.umd.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    /*
      Example kicking off the UI. Obviously, adapt this to your specific needs.
      Assumes you have a <div id="q-app"></div> in your <body> above
     */
      new Vue({
        el: '#q-app',
        data: function () {
          return {
            userColumns: [
                { name: 'name', align: 'center', label: 'Name', field: 'name', sortable: true },
                { name: 'email', align: 'center', label: 'Email', field: 'email', sortable: true },
                { name: 'approved', align: 'center', label: 'Approved', field: 'approved', sortable: true }
            ],
            users: users
          }
        },
        methods: {
            approvedValue (val) {
                console.log(val == 1)
                return val == 1
            },
            updateApprovalValue (row, val) {
                const res = axios.post('/api/userApproval', {
                    id: row.id,
                    approved: val
                });
                // console.log(row, val)
                // make an axios request to update user
            },
            updateUserApproval (row, val, evt) {
                console.log(val, evt)
                this.users.map(x => {
                    if (row.id === x.id) {
                        x.appoved = val
                        console.log(x, row)
                    }
                })
            },
        },
        // ...etc
      })
</script>