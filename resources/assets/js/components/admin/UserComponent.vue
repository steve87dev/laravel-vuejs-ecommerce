<template>
    <div>
        <h3>Users</h3>

        <div class="row">
            <div class="col-md-10"></div>
            <div class="col-md-2">
                <label class="btn btn-primary" @click="addUser()" >Create User</label>
            </div>
        </div><br />

        <table class="table table-hover">
            <thead>
            <tr>
                <td>ID</td>
                <td>Name</td>
                <td>Email</td>
                <td colspan="2">Actions</td>
            </tr>
            </thead>

            <tbody>
            <tr v-for="user, index in users">
                <td>{{ user.id }}</td>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td><label @click="editUser(index)" class="btn btn-success">Edit</label></td>
                <td><button class="btn btn-danger" @click="deleteUser(index)">Delete</button></td>

            </tr>
            </tbody>
        </table>

        <div class="modal fade" tabindex="-1" role="dialog" id="add_user_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" name="name" id="name" placeholder="User Name" class="form-control"
                                   v-model="user.name">
                        </div>
                        <div class="form-group">
                            <label for="name">Email:</label>
                            <input type="text" name="email" id="email" placeholder="Email" class="form-control"
                                   v-model="user.email">
                        </div>
                        <div class="form-group">
                            <label for="name">Password:</label>
                            <input type="text" name="password" id="password" placeholder="Password" class="form-control"
                                   v-model="user.password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="createUser" class="btn btn-primary">Submit</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div class="modal fade" tabindex="-1" role="dialog" id="edit_user_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" name="name" id="name" placeholder="User Name" class="form-control"
                                   v-model="edit_user.name">
                        </div>
                        <div class="form-group">
                            <label for="name">Email:</label>
                            <input type="text" name="email" id="email" placeholder="Email" class="form-control"
                                   v-model="edit_user.email">
                        </div>
                        <div class="form-group">
                            <label for="name">Password:</label>
                            <input type="text" name="password" id="password" placeholder="Password" class="form-control"
                                   v-model="edit_user.password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="updateUser()" class="btn btn-primary">Submit</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </div>
</template>

<script>
    export default {
        data:function(){
          return {
              users: [],
              user: {},
              edit_user:{},
              errors: []
          }
        },
        created: function() {
            this.getUsers();
        },
        methods:{
            addUser: function()
            {
                this.errors = [];
                $("#add_user_model").modal("show");
            },
            createUser: function()
            {
                var myThis = this;
                this.errors = [];
                axios.post('manage-users', this.user)
                        .then(response => {
                            this.reset();
                            this.getUsers();
                            $("#add_user_model").modal("hide");

                        })
                        .catch(error => {
                            this.errors = [];
                            if (error.response.data.errors.name) {
                                this.errors.push(error.response.data.errors.name[0]);
                            }
                            if (error.response.data.errors.email) {
                                this.errors.push(error.response.data.errors.email[0]);
                            }
                            if (error.response.data.errors.password) {
                                this.errors.push(error.response.data.errors.password[0]);
                            }
                        });
            },
            editUser(index)
            {
                this.errors = [];
                $("#edit_user_model").modal("show");
                this.edit_user = this.users[index];
                this.edit_user.password = null;
            },
            updateUser: function()
            {
                var myThis = this;
                this.errors = [];
                axios.put('manage-users/' + this.edit_user.id, this.edit_user)
                        .then(response => {
                            this.reset();
                            this.getUsers();
                            $("#edit_user_model").modal("hide");

                        })
                    .catch(error => {
                        this.errors = [];
                        if (error.response.data.errors.name) {
                            this.errors.push(error.response.data.errors.name[0]);
                        }
                        if (error.response.data.errors.email) {
                            this.errors.push(error.response.data.errors.email[0]);
                        }
                        if (error.response.data.errors.password) {
                            this.errors.push(error.response.data.errors.password[0]);
                        }
                    });
            },
            reset()
            {
                this.user = [];
            },
            getUsers: function(){
                var myThis = this;
                axios.get('manage-users')
                        .then(function (result) {
                            myThis.users = result.data;
                        })
                        .catch(function (result) {
                            console.log(result);
                        });
            },
            deleteUser(index)
            {

                let conf = confirm("Do you ready want to delete this User?");
                if (conf === true) {
                    axios.delete('manage-users/' + this.users[index].id)
                            .then(response => {
                        this.users.splice(index, 1);
                })
                .catch(error => {

                    });
                }
            }
        }
    }
</script>
