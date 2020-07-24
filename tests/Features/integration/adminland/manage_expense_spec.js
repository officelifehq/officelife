describe('Adminland - Expenses', function () {
  it('should let you manage expense categories as an administrator', function () {
    cy.login()

    cy.createCompany()

    cy.visit('/1/account')
    cy.get('[data-cy=expenses-admin-link]').click()

    // open the popup
    cy.get('[data-cy=add-category-button]').click()

    // start to populate it and press save
    cy.get('[data-cy=add-title-input]').type('travel')
    cy.get('[data-cy=modal-add-cta]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=categories-list]').contains('travel')
    cy.hasAuditLog('Created the expense category called travel', '/1/account/expenses')
  })

  it('should let you manage expense categories as an HR', function () {
    cy.login()

    cy.createCompany()

    cy.changePermission(1, 200)

    cy.visit('/1/account')
    cy.get('[data-cy=expenses-admin-link]').click()

    // open the popup
    cy.get('[data-cy=add-category-button]').click()

    // start to populate it and press save
    cy.get('[data-cy=add-title-input]').type('travel')
    cy.get('[data-cy=modal-add-cta]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=categories-list]').contains('travel')
  })

  it('should let you manage accountants as an administrator', function () {
    cy.login()

    cy.createCompany()

    // check that the user, regardless of their roles, has not access to the
    // expenses tab on the dashboard
    cy.visit('/1/dashboard')
    cy.get('[data-cy=dashboard-expenses-tab]').should('not.exist')

    // go to the adminland and manage accountants
    cy.visit('/1/account')
    cy.get('[data-cy=expenses-admin-link]').click()

    // activate edit mode
    cy.get('[data-cy=show-edit-mode]').click()
    cy.get('[data-cy=hide-edit-mode]').click()
    cy.get('[data-cy=show-edit-mode]').click()
    cy.get('[data-cy=potential-employees]').type('admin')
    cy.get('[data-cy=employee-id-1-add]').click()
    cy.get('[data-cy=hide-edit-mode]').click()

    // check to see if the list of accountants contains the newly added employee
    cy.get('[data-cy=employees-list]').contains('admin@admin.com')

    cy.hasAuditLog('Allowed admin@admin.com to manage company’s expense', '/1/account/expenses')
    cy.hasEmployeeLog('Has been allowed to manage company’s expenses', '/1/dashboard')
    cy.hasNotification('You are now allowed to manage company’s expenses')

    // check that the dashboard contains the expenses tab
    cy.visit('/1/dashboard')
    cy.get('[data-cy=dashboard-expenses-tab]').should('not.exist')

    // go to the adminland to remove the accountant
    cy.visit('/1/account')
    cy.get('[data-cy=expenses-admin-link]').click()
    cy.get('[data-cy=show-edit-mode]').click()
    cy.get('[data-cy=remove-employee-1]').click()
    cy.hasAuditLog('Disallowed admin@admin.com to manage company’s expense', '/1/account/expenses')
    cy.hasEmployeeLog('Has been disallowed to manage company’s expenses', '/1/dashboard')
  })

  it('should let you manage accountants as HR', function () {
    cy.login()

    cy.createCompany()

    cy.changePermission(1, 200)

    // check that the user, regardless of their roles, has not access to the
    // expenses tab on the dashboard
    cy.visit('/1/dashboard')
    cy.get('[data-cy=dashboard-expenses-tab]').should('not.exist')

    // go to the adminland and manage accountants
    cy.visit('/1/account')
    cy.get('[data-cy=expenses-admin-link]').click()

    // activate edit mode
    cy.get('[data-cy=show-edit-mode]').click()
    cy.get('[data-cy=hide-edit-mode]').click()
    cy.get('[data-cy=show-edit-mode]').click()
    cy.get('[data-cy=potential-employees]').type('admin')
    cy.get('[data-cy=employee-id-1-add]').click()
    cy.get('[data-cy=hide-edit-mode]').click()

    // check to see if the list of accountants contains the newly added employee
    cy.get('[data-cy=employees-list]').contains('admin@admin.com')

    cy.hasEmployeeLog('Has been allowed to manage company’s expenses', '/1/dashboard')
    cy.hasNotification('You are now allowed to manage company’s expenses')

    // check that the dashboard contains the expenses tab
    cy.visit('/1/dashboard')
    cy.get('[data-cy=dashboard-expenses-tab]').should('not.exist')

    // go to the adminland to remove the accountant
    cy.visit('/1/account')
    cy.get('[data-cy=expenses-admin-link]').click()
    cy.get('[data-cy=show-edit-mode]').click()
    cy.get('[data-cy=remove-employee-1]').click()
    cy.hasEmployeeLog('Has been disallowed to manage company’s expenses', '/1/dashboard')
  })
})
