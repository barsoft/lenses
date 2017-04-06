<h1>Lenses</h1>

<h2>Description</h2>
Simple application for prediction of expiration date of purchased eye lenses in PHP 7. No frameworks used.

<h2>Issue</h2>


**The PHP Task**

The contact lenses are repeatedly purchased goods. It’s crucial to precisely predict when our
customers will need their next package of contact lenses. We have to calculate the correct date
and remind them about their purchase through an e-mail.
Write a PHP script that predicts the date the customer will be out of contact lenses. Base the
calculation on the history of his orders and the duration of contact lenses he has ordered.

**Information about contact lenses**

Use the following array. The key is the ID of the good and the value is the duration of the good
in days.
    
    $goods = array(    
        1 => 180, // Biofinity (6 lenses)    
        2 => 90, // Biofinity (3 lenses)    
        3 => 30, // Focus Dailies (30)    
    );

Biofinity are monthly contact lenses. It means one pair lasts one month. Focus Dailies are daily
contact lenses. It means one pair lasts just one day. The customers often wear their contact
lenses longer than is recommended. So you cannot use just data about the duration of the good
but also the history of customer’s orders.

**History of customer’s orders**

Use the following array. The key is the date and the value is the array of orders. Each subarray
has three values: ID of the good, quantity and contact lens power.

Keep in mind that every customer uses one contact lens on each eye. It doesn’t mean that the
contact lenses have to have the same parameters on both eyes (power).
First customer history of orders:

    $orders = array(
        ‘2015-04-01’ => array(
        array(1, 2, ‘-2.00’),
        array(1, 2, ‘-3.00’),
        ),
    );

Second customer history of orders:

    $orders = array(
        ‘2014-10-01’ => array(
            array(3, 2, ‘-1.50’),
            array(3, 2, ‘-3.50’),
        ),
        ‘2015-01-01’ => array(
            array(3, 2, ‘-1.50’),
            array(3, 2, ‘-3.50’),
        ),
        ‘2015-04-15’ => array(
            array(3, 2, ‘-1.50’),
            array(3, 2, ‘-3.50’),
        ),
    );

Third customer history of orders:
    
    $orders = array(
        ‘2014-08-01’ => array(
        array(2, 2, ‘+0.50’),
        ),
    );

The task should take you maximum 3 hours. Let us know how much time it really took.

***Maksym Prysiazhnyi 2017***