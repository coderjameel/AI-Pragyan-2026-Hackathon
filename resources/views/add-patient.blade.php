<div>

    <form action="/add-patient" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Enter patient name: ">
        <input type="number" name="age" placeholder="Enter patient age:">
        <select name="gender">
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>
        <input type="submit" value="Add Patient">
    </form>

</div>
