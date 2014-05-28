<div class="panel panel-default">
  <div class="panel-body">
    <form role="form" action="?process=ticket_widget" method="post">
      <div class="form-group">
        <label for="ev_ticket-first-name">First Name</label>
        <input type="text" class="form-control" name="ev_ticket-first-name" id="ev_ticket-first-name" placeholder="First Name">
      </div>
      <div class="form-group">
        <label for="ev_ticket-last-name">Last Name</label>
        <input type="text" class="form-control" name="ev_ticket-last-name" id="ev_ticket-last-name" placeholder="Last Name">
      </div>
      <div class="form-group">
        <label for="ev_ticket-email">Email</label>
        <input type="email" class="form-control" name="ev_ticket-email" id="ev_ticket-email" placeholder="Email">
      </div>
      <div class="form-group">
        <label for="ev_ticket-phone">Phone</label>
        <input type="text" class="form-control" name="ev_ticket-phone" id="ev_ticket-phone" placeholder="Phone">
      </div>
      <div class="form-group row">
        <label for="ev_ticket-qty" class="col-sm-8 control-label">Qty</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" name="ev_ticket-qty" id="ev_ticket-qty" placeholder="Qty">
        </div>
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </form>
  </div>
</div>