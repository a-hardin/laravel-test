
<script>
    var users = @json($users);
    var current_user = @json($current_user);
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
                                    title="User Approval"
                                    :data="users"
                                    :columns="userColumns"
                                    row-key="name"
                                    binary-state-sort
                                    :loading="loadingApprovedUsers"
                                    >
                                        <template v-slot:header="props">
                                            <q-tr :props="props">
                                                <q-th key="approved" >
                                                    Approved
                                                </q-th>
                                                <q-th
                                                    v-for="col in props.cols"
                                                    :key="col.name"
                                                    :props="props"
                                                >
                                                    {{ col.label }}
                                                </q-th>
                                            </q-tr>
                                        </template>
                                        <template v-slot:body="props">
                                            <q-tr :props="props">
                                                <q-td auto-width>
                                                    <q-toggle
                                                        v-model="props.row.approved"
                                                        :true-value="1"
                                                        :false-value="0"
                                                        @input="(val) => updateApprovalValue(props.row, val)"
                                                    />
                                                </q-td>
                                                <q-td
                                                    v-for="col in props.cols"
                                                    :key="col.name"
                                                    :props="props"
                                                >
                                                    {{ col.value }}
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
                { name: 'email', align: 'center', label: 'Email', field: 'email', sortable: true }
            ],
            users: users,
            loadingApprovedUsers: false
          }
        },
        methods: {
            approvedValue (val) {
                return val == 1
            },
            updateApprovalValue (row, val) {
                this.loadingApprovedUsers = true
                const res = axios.post('/api/userApproval', {
                    id: row.id,
                    approved: val,
                    api_token: current_user.api_token
                }).then(response => {
                    if (response.data.status === 'success') {
                        this.showNotify('Approval updated', 'green')
                    } else {
                        this.showNotify('Failed approval update', 'red')
                    }
                    this.loadingApprovedUsers = false
                }).catch(error => {
                    this.showNotify(error, 'red')
                    this.loadingApprovedUsers = false
                });
            },
            showNotify (message, color) {
                this.$q.notify({
                    message: message,
                    color: color
                })
            }
        },
      })
</script>