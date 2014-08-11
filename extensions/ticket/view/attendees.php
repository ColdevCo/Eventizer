<style type="text/css">
    .wrap {
        margin: 30px 30px 30px 10px;
        padding: 15px 15px 40px 15px;
        background-color: #fff;
    }

    .wrap > h2 {
        margin-bottom: 15px;
    }

    .ev_attendee_filter {
        background-color: #F1F1F1;
        margin: 0 -15px;
        padding: 30px 40px;
    }

    .ev_attendee_filter > div.input-group {
        margin: 0 5px 10px;
    }

    div.input-group > label.left {
        display: inline-block;
        width: 80px;
        vertical-align: top;
    }


    #ev_attendee_event_name,
    #ev_attendee_name,
    #ev_attendee_email,
    #ev_attendee_phone {
        width: 275px;
        height: 100%;
        padding: 5px;
    }

    #ev_attendee_submit {
        display: inline-block;
        cursor: pointer;
        background-color: #16499A;
        color: #FFF;
        padding: 6px 30px;
        margin: 25px 0 0;
        text-align: center;
        vertical-align: middle;
        border: 1px solid transparent;
        -webkit-border-radius: 0;
        -moz-border-radius: 0;
        border-radius: 0;
    }

    table.attendees {
        width: 100%;
        max-width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin: 40px 0 20px;
    }

    table.attendees th,
    table.attendees td {
        padding: 8px;
        font-size: 14px;
        line-height: 14pt;
        text-align: left;
    }

    table.attendees th {
        border-bottom: 1px solid #111;
        vertical-align: bottom;
    }

    table.attendees td {
        border-bottom: 1px solid #EAEAEA;
        vertical-align: middle;
    }
</style>

<div id="event-attendees" class="wrap">
    <h2>Attendees</h2>

    <div class="ev_attendee_filter">

        <div class="input-group">

            <label for="ev_attendee_event_name" class="left">Event</label>
            <input type="text" id="ev_attendee_event_name" name="ev_attendee_event_name" />

        </div>

        <div class="input-group">

            <label for="ev_attendee_name" class="left">Name</label>
            <input type="text" id="ev_attendee_name" name="ev_attendee_name" />

        </div>

        <div class="input-group">

            <label for="ev_attendee_email" class="left">Email</label>
            <input type="email" id="ev_attendee_email" name="ev_attendee_email" />

        </div>

        <div class="input-group">

            <label for="ev_attendee_phone" class="left">Phone</label>
            <input type="text" id="ev_attendee_phone" name="ev_attendee_phone" />

        </div>

        <div class="input-group">

            <button id="ev_attendee_submit">Filter</button>

        </div>

    </div>

    <table class="attendees">
        <thead>
        <tr>
            <th>No.</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Buy</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>Naufal Faruqi</td>
            <td>naufal.faruqi2010@gmail.com</td>
            <td>-</td>
            <td>2 VIP Tickets</td>
        </tr>
        <tr>
            <td>1</td>
            <td>Naufal Faruqi</td>
            <td>naufal.faruqi2010@gmail.com</td>
            <td>-</td>
            <td>2 VIP Tickets</td>
        </tr>
        <tr>
            <td>1</td>
            <td>Naufal Faruqi</td>
            <td>naufal.faruqi2010@gmail.com</td>
            <td>-</td>
            <td>2 VIP Tickets</td>
        </tr>
        <tr>
            <td>1</td>
            <td>Naufal Faruqi</td>
            <td>naufal.faruqi2010@gmail.com</td>
            <td>-</td>
            <td>2 VIP Tickets</td>
        </tr>
        <tr>
            <td>1</td>
            <td>Naufal Faruqi</td>
            <td>naufal.faruqi2010@gmail.com</td>
            <td>-</td>
            <td>2 VIP Tickets</td>
        </tr>
        <tr>
            <td>1</td>
            <td>Naufal Faruqi</td>
            <td>naufal.faruqi2010@gmail.com</td>
            <td>-</td>
            <td>2 VIP Tickets</td>
        </tr>
        </tbody>
    </table>
</div>