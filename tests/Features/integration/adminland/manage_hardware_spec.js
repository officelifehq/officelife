describe('Adminland - Hardware', function () {
  it('should let you manage company hardware as an administrator', function () {
    cy.login()

    cy.createCompany()

    cy.visit('/1/account')
    cy.get('[data-cy=hardware-admin-link]').click()

    // blank state should exist
    cy.get('[data-cy=hardware-blank-message]').should('exist')

    //add one hardware without serial number
    cy.get('[data-cy=add-hardware-button]').click()
    cy.get('[data-cy=hardware-name-input]').type('iPhone Android 18')
    cy.get('[data-cy=submit-add-hardware-button]').click()

    cy.get('[data-cy=hardware-total]').contains('1')
    cy.get('[data-cy=hardware-count-not-lent]').contains('1')
    cy.get('[data-cy=hardware-count-lent]').contains('0')
    cy.get('[data-cy=hardware-item-1]').contains('iPhone Android 18')

    cy.hasAuditLog('Added a hardware called', '/1/account/hardware')

    //add one hardware with serial number
    cy.get('[data-cy=add-hardware-button]').click()
    cy.get('[data-cy=hardware-name-input]').type('Android ShitPhone')
    cy.get('[data-cy=hardware-serial-input]').type('1234')
    cy.get('[data-cy=submit-add-hardware-button]').click()

    cy.get('[data-cy=hardware-total]').contains('2')
    cy.get('[data-cy=hardware-count-not-lent]').contains('2')
    cy.get('[data-cy=hardware-count-lent]').contains('0')
    cy.get('[data-cy=hardware-item-2]').contains('Android ShitPhone')
    cy.get('[data-cy=hardware-item-2]').contains('1234')

    cy.hasAuditLog('Added a hardware called', '/1/account/hardware')

    // add one hardware and associate an employee
    cy.get('[data-cy=add-hardware-button]').click()
    cy.get('[data-cy=hardware-name-input]').type('Blackberry 18')
    cy.get('[data-cy=hardware-serial-input]').type('4565')
    cy.get('[data-cy=lend-hardware-checkbox]').check()
    cy.get('[data-cy=employee-selector]').click()
    cy.get('ul.vs__dropdown-menu>li').eq(0).click()
    cy.get('[data-cy=employee-selector]').click()
    cy.get('[data-cy=submit-add-hardware-button]').click()

    cy.get('[data-cy=hardware-total]').contains('3')
    cy.get('[data-cy=hardware-count-not-lent]').contains('2')
    cy.get('[data-cy=hardware-count-lent]').contains('1')
    cy.get('[data-cy=hardware-item-3]').contains('Blackberry 18')
    cy.get('[data-cy=hardware-item-3]').contains('4565')
    cy.get('[data-cy=hardware-item-lend-3]').contains('admin@admin.com')

    cy.hasAuditLog('Added a hardware called', '/1/account/hardware')

    // see the details of a hardware
    cy.get('[data-cy=hardware-item-3]').click();
    cy.url().should('include', '/1/account/hardware/3')

    // edit the hardware
    cy.get('[data-cy=hardware-edit-link-3]').click()
    cy.get('[data-cy=hardware-name-input]').type('Cable 4 inches')
    cy.get('[data-cy=hardware-serial-input]').type('1234')
    cy.get('[data-cy=submit-edit-hardware-button]').click()

    cy.get('[data-cy=item-name').contains('Cable 4 inches')
    cy.get('[data-cy=item-serial-number]').contains('1234')

    cy.hasAuditLog('Updated the hardware', '/1/account/hardware/3')

    // delete the hardware
    cy.get('[data-cy=delete-button]').click()
    cy.get('[data-cy=delete-cancel-button]').click()
    cy.get('[data-cy=delete-button]').click()
    cy.get('[data-cy=delete-confirm-button]').click()

    cy.hasAuditLog('Deleted the hardware called', '/1/account/hardware/3')
  })

  it('should let you manage hardware as an HR', function () {
    cy.login()

    cy.createCompany()

    cy.visit('/1/account')

    cy.changePermission(1, 200)
    cy.get('[data-cy=hardware-admin-link]').click()

    // blank state should exist
    cy.get('[data-cy=hardware-blank-message]').should('exist')

    //add one hardware without serial number
    cy.get('[data-cy=add-hardware-button]').click()
    cy.get('[data-cy=hardware-name-input]').type('iPhone Android 18')
    cy.get('[data-cy=submit-add-hardware-button]').click()

    cy.get('[data-cy=hardware-total]').contains('1')
    cy.get('[data-cy=hardware-count-not-lent]').contains('1')
    cy.get('[data-cy=hardware-count-lent]').contains('0')
    cy.get('[data-cy=hardware-item-1]').contains('iPhone Android 18')

    //add one hardware with serial number
    cy.get('[data-cy=add-hardware-button]').click()
    cy.get('[data-cy=hardware-name-input]').type('Android ShitPhone')
    cy.get('[data-cy=hardware-serial-input]').type('1234')
    cy.get('[data-cy=submit-add-hardware-button]').click()

    cy.get('[data-cy=hardware-total]').contains('2')
    cy.get('[data-cy=hardware-count-not-lent]').contains('2')
    cy.get('[data-cy=hardware-count-lent]').contains('0')
    cy.get('[data-cy=hardware-item-2]').contains('Android ShitPhone')
    cy.get('[data-cy=hardware-item-2]').contains('1234')

    // add one hardware and associate an employee
    cy.get('[data-cy=add-hardware-button]').click()
    cy.get('[data-cy=hardware-name-input]').type('Blackberry 18')
    cy.get('[data-cy=hardware-serial-input]').type('4565')
    cy.get('[data-cy=lend-hardware-checkbox]').check()
    cy.get('[data-cy=employee-selector]').click()
    cy.get('ul.vs__dropdown-menu>li').eq(0).click()
    cy.get('[data-cy=employee-selector]').click()
    cy.get('[data-cy=submit-add-hardware-button]').click()

    cy.get('[data-cy=hardware-total]').contains('3')
    cy.get('[data-cy=hardware-count-not-lent]').contains('2')
    cy.get('[data-cy=hardware-count-lent]').contains('1')
    cy.get('[data-cy=hardware-item-3]').contains('Blackberry 18')
    cy.get('[data-cy=hardware-item-3]').contains('4565')
    cy.get('[data-cy=hardware-item-lend-3]').contains('admin@admin.com')

    // see the details of a hardware
    cy.get('[data-cy=hardware-item-3]').click();
    cy.url().should('include', '/1/account/hardware/3')

    // edit the hardware
    cy.get('[data-cy=hardware-edit-link-3]').click()
    cy.get('[data-cy=hardware-name-input]').type('Cable 4 inches')
    cy.get('[data-cy=hardware-serial-input]').type('1234')
    cy.get('[data-cy=submit-edit-hardware-button]').click()

    cy.get('[data-cy=item-name').contains('Cable 4 inches')
    cy.get('[data-cy=item-serial-number]').contains('1234')

    // delete the hardware
    cy.get('[data-cy=delete-button]').click()
    cy.get('[data-cy=delete-cancel-button]').click()
    cy.get('[data-cy=delete-button]').click()
    cy.get('[data-cy=delete-confirm-button]').click()
  })
})
