@extends('layouts.app')

@section('title', 'Checkout - Buitenworks')

@section('content')
    <main class="container-main" style="margin-top: 100px;">
        <h2>Checkout</h2>

        <form action="" method="POST" enctype="multipart/form-data" class="checkout-form">
            @csrf
            <div class="form-group">
                <label>Shipping Address</label>
                <textarea name="address" required class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label>Payment Proof (Manual Transfer)</label>
                <input type="file" name="payment_proof" required>
            </div>

            <button type="submit" class="btn primary">Submit Order</button>
        </form>
    </main>
@endsection