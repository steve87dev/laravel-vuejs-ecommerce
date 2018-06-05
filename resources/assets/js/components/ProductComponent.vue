<template>


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="alert alert-success" v-if="message">
                    {{ message }}
                </div>
            </div>
            <div class="col-md-10"></div>
            <div class="col-md-2"><a @click="showCart">Cart: {{ cartCount }}</a></div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-3" v-for="product, index in products" style="height: auto; padding: 5px; margin:3px; text-align:center; width: 85%; border: solid 1px #ccc;">
                <div >
                <img :src="'img/'+product.image" style="height: auto; padding: 1px; width: 100%; border: solid 1px #ccc;"><br/>
                </div>
                <label>{{ product.product_title }}</label><br/>
                <label><b>Price: Rs {{ product.price }}</b></label><br/>
                <label class="btn btn-success" v-on:click="addToCart(product.id)" >Add To Cart</label>
            </div>

        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="cart_items_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="cartMessage">
                            {{ cartMessage }}
                        </div>
                        <table class="table table-hover" v-if="cartItems.length >0 ">
                            <thead>
                            <tr>
                                <td>Product Image</td>
                                <td>Product Name</td>
                                <td>Price</td>
                                <td colspan="2">Actions</td>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="cart, index in cartItems">
                                <td><img :src="'img/'+cart.products.image" style="height: auto; padding: 1px; border: solid 1px #ccc;" width="100" height="100"></td>
                                <td>{{ cart.products.product_title }}</td>
                                <td><b>Rs {{ cart.products.price }}</b></td>
                                <td><button class="btn btn-danger" @click="deleteCartItem(index)">Remove</button></td>
                            </tr>
                            </tbody>
                        </table>
                        <label v-else="cartItems.length >0 ">No cart items</label>
                    </div>
                    <div class="modal-footer center">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Continue Shopping</button>
                        <button type="button" @click="placeOrder()" v-if="cartItems.length >0 " class="btn btn-primary">Place Order</button>
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
              message:'',
              cartItems:[],
              cartCount:0,
              cartMessage:''
          }
        },
        created: function() {
            this.getProducts();
            this.updateCartCount();
        },
        methods:{
            addToCart: function(id){
                var myThis = this;
                axios.post('api/v1/cart', {
                    id: id
                }).then(function (result) {
                    myThis.message = result.data.message;
                    myThis.updateCartCount();
                })
                .catch(function (result) {
                    console.log(result);
                });

            },
            getProducts: function(){
                var myThis = this;
                axios.get('api/v1/products')
                        .then(function (result) {
                            myThis.products = result.data.data;
                        })
                        .catch(function (result) {
                            console.log(result);
                        });
            },

            updateCartCount: function(){

                var myThis = this;
                axios.get('api/v1/cart')
                        .then(function (result) {
                            myThis.cartItems = result.data;
                            myThis.cartCount = result.data.length;
                        })
                        .catch(function (result) {
                            console.log(result);
                        });
               },
            showCart: function(){
                $("#cart_items_model").modal("show");
            },
            deleteCartItem: function(index)
            {
                var myThis = this;
                let conf = confirm("Do you ready want to remove this Product from cart?");
                if (conf === true) {
                    axios.delete('api/v1/cart/' + this.cartItems[index].id)
                            .then(function (result) {
                                myThis.cartMessage = result.data.message;
                                myThis.updateCartCount();
                            })
                            .catch(function (result) {
                                console.log(result);
                            });
                }
            },
            placeOrder: function(index)
            {
                var myThis = this;
                let conf = confirm("Do you ready want to place order");
                if (conf === true) {
                    axios.get('api/v1/cart-place-order')
                            .then(function (result) {
                                myThis.message = result.data.message;
                                myThis.updateCartCount();
                                $("#cart_items_model").modal("hide");
                            })
                            .catch(function (result) {
                                console.log(result);
                            });
                }
            }


        },

    }
</script>
