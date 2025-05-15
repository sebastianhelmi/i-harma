Create a Laravel 11 feature where a user with role `purchasing` can create a Purchase Order (PO) based on an approved SPB.

System Flow:

1. Only show SPBs where:
    - `status = 'approved'`
    - `status_po = 'pending'`
2. When opening PO creation form (`create($spb_id)`):

    - Load SPB header data
    - Load item detail from either:
        - `site_spbs` if `category_entry = 'site'`
        - `workshop_spbs` if `category_entry = 'workshop'`

3. For each item:

    - Query inventory where `item_name` matches
    - If inventory not found or quantity < required:
        - Mark item as **"need to be ordered"**
        - Add to PO draft form
    - Otherwise:
        - Mark as "available" → No need to add to PO

4. PO Creation Form:

    - Input: Supplier, Order Date, Estimated Usage Date, Remarks
    - Dynamic table of items that need to be ordered:
        - Item name, quantity, unit, unit price (input), total price (auto calc)
    - Show warning if no items need to be ordered (don't allow submit)

5. On submit:

    - Create entry in `pos` table:
        - `po_number` (format: PO-YYYYMM-###)
        - `spb_id`, `created_by`, `supplier_id`, `order_date`, `status = pending`
    - Save `po_items`
        - Link to `spb_id`, and either `site_spb_id` or `workshop_spb_id`
    - Update SPB `status_po = 'ordered'` if at least 1 item ordered

6. Optional:
    - If all items available in inventory, allow skipping PO creation
      and just set `status_po = 'not_required'`

UI Requirements:

-   Blade view: `purchasing.pos.create`
-   Use TailwindCSS or Bootstrap
-   Flash message on success
-   Filter SPB list in `purchasing.pos.index` to only pending-PO ones

Authorization:

-   Middleware: only accessible by `purchasing` role
