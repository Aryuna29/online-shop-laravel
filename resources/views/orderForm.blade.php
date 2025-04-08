
<a href="/profile" class="button16">Мой профиль</a>
<a href="/cart" class="button16">Корзина</a>
<a href="/catalog" class="button16">Каталог</a>
<a href="/user-order" class="button16">Мои заказы</a>
<a href="/logout" class="button16">Выход</a>
<form action="{{route('post.createOrder')}}" method="POST">
    @csrf
    <div class="container">
        <h3>Заказ</h3>
        <hr>
        <label for="name"><b>Имя</b></label>
        @error("name")
        <label for="name" style="color: brown">{{ $message }}</label>
        @enderror
        <input type="text" placeholder="Имя" name="name" id="name" required>

        <label for="phone"><b>Телефон</b></label>
        @error("phone")
        <label for="phone" style="color: brown">{{ $message }}</label>
        @enderror
        <input type="text" placeholder="Номер телефона" name="phone" id="phone" required>

        <label for="address"><b>Адрес</b></label>
        @error("address")
        <label for="address" style="color: brown">{{ $message }}</label>
        @enderror
        <input type="text" placeholder="Название улицы, номер дома, номер квартиры" name="address" id="address" required>

        <label for="comment"><b>Комментарий</b></label>
        @error("comment")
        <label for="comment" style="color: brown">{{ $message }}</label>
        @enderror
        <input type="text" placeholder="Комментарий" name="comment" id="comment">
        <hr>
        <div class="order"><h2>Заказ</h2></div>
        @foreach($userProducts as $userProduct)
        <div class="order"><li><strong>{{$userProduct->product->name}}<br> </strong>
                <label>Стоимость {{$userProduct->amount}} шт * {{$userProduct->product->price}}₽ : {{$userProduct->amount * $userProduct->product->price}}₽</label></li></div>
        @endforeach
        <div class="order"><h2>Общая стоимость: {{$sumTotal}} ₽</h2> </div>
        <hr>
        <button type="submit" class="orderbtn">Оформить заказ</button>
    </div>

</form>

<style>
    h3 {
        display: block;
        margin-bottom: 60px;
        margin-left: 60px;
        font-weight: 1000;
        font-size: 40px;
        line-height: 1.2;
        color: #333333;
        text-align: justify-all;
    }

    * {box-sizing: border-box}


    .container {
        display: inline-block;
        margin-left: 300px;
        margin-right: 300px;
    }

    .order {
        display: flex;
    }

    /* Full-width input fields */
    input[type=text], input[type=password] {
        width: 100%;
        padding: 15px;
        margin: 5px 0 22px 0;
        display: inline-block;
        border: none;
        background: #f1f1f1;
    }



    input[type=text]:focus, input[type=password]:focus {
        background-color: #ddd;
        outline: none;
    }


    /* Overwrite default styles of hr */
    hr {
        border: 1px solid #f1f1f1;
        margin-bottom: 25px;
    }

    /* Set a style for the submit/register button */
    .orderbtn {
        background-color: #04AA6D;
        color: white;
        padding: 16px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        width: 100%;
        opacity: 0.9;
    }

    .orderbtn:hover {
        opacity:1;
    }

    /* Add a blue text color to links */
    a {
        color: dodgerblue;
    }
    a.button16 {
        display: inline-block;
        text-decoration: none;
        padding: 1em;
        outline: none;
        border-radius: 1px;
    }
    a.button16:hover {
        background-image:
            radial-gradient(1px 45% at 0% 50%, rgba(0,0,0,.6), transparent),
            radial-gradient(1px 45% at 100% 50%, rgba(0,0,0,.6), transparent);
    }
    a.button16:active {
        background-image:
            radial-gradient(45% 45% at 50% 100%, rgba(255,255,255,.9), rgba(255,255,255,0)),
            linear-gradient(rgba(255,255,255,.4), rgba(255,255,255,.3));
        box-shadow:
            inset rgba(162,95,42,.4) 0 0 0 1px,
            inset rgba(255,255,255,.9) 0 0 1px 3px;
    }
    <style>
