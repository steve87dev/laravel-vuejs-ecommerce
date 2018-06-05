<template>
    <div>
        <h3>Products</h3>

        <div class="row">
            <div class="col-md-10"></div>
            <div class="col-md-2">
                <label class="btn btn-primary" @click="addProduct()" >Create Product</label>
            </div>
        </div><br />

        <table class="table table-hover">
            <thead>
            <tr>
                <td>ID</td>
                <td>Product Image</td>
                <td>Product Name</td>
                <td>Price</td>
                <td colspan="2">Actions</td>
            </tr>
            </thead>

            <tbody>
            <tr v-for="product, index in products">
                <td>{{ product.id }}</td>
                <td><img :src="'img/'+product.image" style="height: auto; padding: 1px; border: solid 1px #ccc;" width="100" height="100"></td>
                <td>{{ product.product_title }}</td>
                <td><b>Rs {{ product.price }}</b></td>
                <td><label @click="editProduct(index)" class="btn btn-success">Edit</label></td>
                <td><button class="btn btn-danger" @click="deleteProduct(index)">Delete</button></td>

            </tr>
            </tbody>
        </table>

        <div class="modal fade" tabindex="-1" role="dialog" id="add_product_model">
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
                            <input type="text" name="product_title" id="product_title" placeholder="Product Name" class="form-control"
                                   v-model="product.product_title">
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea name="product_description" id="product_description" cols="30" rows="5" class="form-control"
                                      placeholder="Product Description" v-model="product.product_description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="name">Price:</label>
                            <input type="number" name="price" id="price" placeholder="Product price" class="form-control"
                                   v-model="product.price">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="createProduct" class="btn btn-primary">Submit</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div class="modal fade" tabindex="-1" role="dialog" id="edit_product_model">
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
                            <input type="text" name="product_title" id="product_title" placeholder="Product Name" class="form-control"
                                   v-model="edit_product.product_title">
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea name="product_description" id="product_description" cols="30" rows="5" class="form-control"
                                      placeholder="Product Description" v-model="edit_product.product_description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="name">Price:</label>
                            <input type="number" name="price" id="price" placeholder="Product price" class="form-control"
                                   v-model="edit_product.price">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="updateProduct()" class="btn btn-primary">Submit</button>
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
              products: [],
              product: {},
              edit_product:{},
              errors: []
          }
        },
        created: function() {
            this.getProducts();
        },
        methods:{
            addProduct: function()
            {
                this.errors = [];
                $("#add_product_model").modal("show");
            },
            createProduct: function()
            {
                var myThis = this;
                this.errors = [];
                this.product.image = 'image_0.jpg';
                this.product.status = 'instock';
                axios.post('admin/products', this.product)
                        .then(response => {
                            this.reset();
                            this.getProducts();
                            $("#add_product_model").modal("hide");

                        })
                        .catch(error => {
                            this.errors = [];
                            if (error.response.data.errors.product_title) {
                                this.errors.push(error.response.data.errors.product_title[0]);
                            }
                            if (error.response.data.errors.price) {
                                this.errors.push(error.response.data.errors.price[0]);
                            }
                        });
            },
            editProduct(index)
            {
                this.errors = [];
                $("#edit_product_model").modal("show");
                this.edit_product = this.products[index];
            },
            updateProduct: function()
            {
                var myThis = this;
                this.errors = [];
                this.edit_product.image = 'image_0.jpg';
                this.edit_product.status = 'instock';
                axios.put('admin/products/' + this.edit_product.id, this.edit_product)
                        .then(response => {
                            this.reset();
                            this.getProducts();
                            $("#edit_product_model").modal("hide");

                        })
                    .catch(error => {
                        this.errors = [];
                        if (error.response.data.errors.product_title) {
                            this.errors.push(error.response.data.errors.product_title[0]);
                        }
                        if (error.response.data.errors.price) {
                            this.errors.push(error.response.data.errors.price[0]);
                        }
                    });
            },
            reset()
            {
                this.product = [];
            },
            getProducts: function(){
                var myThis = this;
                axios.get('admin/products')
                        .then(function (result) {
                            myThis.products = result.data.data;
                        })
                        .catch(function (result) {
                            console.log(result);
                        });
            },
            deleteProduct(index)
            {

                let conf = confirm("Do you ready want to delete this Product?");
                if (conf === true) {
                    axios.delete('admin/products/' + this.products[index].id)
                            .then(response => {
                        this.products.splice(index, 1);
                })
                .catch(error => {

                    });
                }
            }
        }
    }
</script>
