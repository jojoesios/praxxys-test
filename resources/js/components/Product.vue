<template>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Products List</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <router-link to="/home">Home</router-link>
                            </li>
                            <li class="breadcrumb-item active">
                                Products List
                            </li>
                        </ol>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <multiselect
                                            v-model="filter_category_by"
                                            :options="categories"
                                            :searchable="true"
                                            :showLabels="false"
                                            :allow-empty="false"
                                            label="name"
                                            track-by="name"
                                            :close-on-select="true"
                                            placeholder="Choose category"
                                            @input="getPagination()"
                                        ></multiselect>
                                    </div>
                                    <input
                                        class="
                                            form-control form-control-sidebar
                                        "
                                        @keyup="$parent.searchresult"
                                        type="search"
                                        placeholder="Search"
                                        aria-label="Search"
                                        v-model="searchtext"
                                    />
                                    <div class="input-group-append">
                                        <button
                                            class="btn btn-primary"
                                            @click="$parent.searchresult"
                                        >
                                            <i class="fas fa-search fa-fw"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <router-link
                                    to="/create-product"
                                    class="btn btn-success float-right mb-2"
                                >
                                    Create <span class="fa fa-plus"></span
                                ></router-link>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <!-- Projects table -->
                            <table class="table table-hover align-items-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Category</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(product, key) in products.data"
                                        :key="key"
                                    >
                                        <th scope="row">
                                            {{ product.product_name }}
                                        </th>
                                        <th>{{ product.category.name }}</th>
                                        <th>
                                            {{
                                                product.stripped_description
                                                    | truncate(30)
                                            }}
                                        </th>
                                        <th>
                                            {{
                                                product.date_and_time
                                                    | moment(
                                                        "MMMM D, YYYY h:m A"
                                                    )
                                            }}
                                        </th>
                                        <th>
                                            <router-link
                                                :to="
                                                    '/update-product/' +
                                                    product.id
                                                "
                                            >
                                                <span
                                                    class="fa fa-edit"
                                                    style="color: blue"
                                                ></span>
                                            </router-link>
                                            /
                                            <a
                                                href="#"
                                                @click="
                                                    deleteRecord(product.id)
                                                "
                                                ><span
                                                    class="fa fa-trash"
                                                    style="color: red"
                                                ></span
                                            ></a>
                                        </th>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <pagination
                                        :data="products"
                                        @pagination-change-page="getPagination"
                                    ></pagination>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
</template>

<script>
export default {
    data() {
        return {
            searchtext: "",
            isEdit: false,
            products: {},
            categories: [],
            filter_category_by: { id: 0, name: "All" },
            product_form: new form({
                id: "",
            }),
        };
    },
    methods: {
        loadRecords() {
            axios
                .get(
                    "api/products?search=" +
                        this.searchtext +
                        "&filterCategory=" +
                        this.filter_category_by.id
                )
                .then(({ data }) => {
                    console.log(data);
                    this.products = data.products;
                    this.categories = data.categories;
                });
        },
        newRecord() {
            this.isEdit = false;
            this.item_form.errors.errors = {};
            this.item_form.reset();

            $("#itemModal").modal({ show: true });
        },
        deleteRecord(id) {
            swal.fire({
                title: "Are you sure?",
                text: "This product will be deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Proceed",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.product_form
                        .delete("api/products/" + id)
                        .then(({ data }) => {
                            console.log(data);
                            swal.fire(
                                "Delete",
                                "Product deleted successfully!.",
                                "success"
                            );
                            run.$emit("afterEvent");
                        })
                        .catch((e) => {});
                }
            });
        },
        getPagination(page = 1) {
            axios
                .get(
                    "api/products?page=" +
                        page +
                        "&search=" +
                        this.searchtext +
                        "&filterCategory=" +
                        this.filter_category_by.id
                )
                .then((data) => {
                    console.log(data);
                    this.products = data.data.products;
                })
                .catch(() => {});
        },
        filterByCategory() {
            console.log(this.filter_category_by);
        },
    },
    created() {
        this.loadRecords();

        run.$on("afterEvent", () => {
            this.loadRecords();
        });

        run.$on("searching", () => {
            axios
                .get(
                    "api/products?search=" +
                        this.searchtext +
                        "&filterCategory=" +
                        this.filter_category_by.id
                )
                .then(({ data }) => {
                    console.log(data);
                    this.products = data.products;
                });
        });
    },
};
</script>