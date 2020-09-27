describe('Employee - Complete expense management', function () {
  it('should let an employeee log an expense, let his manager accept it and let the accountant validate it', function () {
    cy.loginLegacy()

    cy.createCompany()

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'user', true)

    cy.visit('/1/employees/1')

    // assign Michael scott as the manager so we can validate a task has been created for the manager later on
    cy.assignManager('scott')

    // grant the accountant right to this manager, so he can also give the final acceptance
    cy.grantAccountantRight('scott',2)

    // now go back to the dashboard and create an expense
    cy.createExpense('restaurant', 123)

    // check that the expense appears in the list of the expenses on the employee dashboard
    cy.get('[data-cy=expenses-list]').contains('restaurant')
    cy.get('[data-cy=expense-1-status-manager_approval]').should('exist')

    cy.hasAuditLog('Created an expense about restaurant for an amount of $123.00', '/1/account/expenses')
    cy.hasEmployeeLog('Created an expense about restaurant for an amount of $123.00', '/1/dashboard')

    // now login to the Michael Scott's account to check the notification
    cy.visit('/1/account/employees')
    cy.get("[name='Michael Scott']").invoke('attr', 'data-invitation-link').then((link) => {
      cy.acceptInvitationLinkAndGoToDashboard('admin2020', link)
      cy.hasNotification('You have a new expense to validate for admin@admin.com')

      // check inside the manager tab on the dashboard that the expense is waiting to be validated
      cy.visit('/1/dashboard/manager')
      cy.get('[data-cy=expense-list-item-1]').contains('restaurant')

      // click on the expense to see it and accept it
      cy.get('[data-cy=expense-cta-1]').click()
      cy.get('[data-cy=expense-title]').contains('restaurant')
      cy.get('[data-cy=expense-amount]').contains('123')

      // accept it
      cy.get('[data-cy=expense-accept-button]').click()
      cy.visit('/1/employees/2/logs')
      cy.contains('Accepted an expense')

      // check that the manager tab does not contain expenses anymore
      cy.visit('/1/dashboard/manager')
      cy.get('[data-cy=expense-list-blank-state]').should('exist')

      //

      //
      cy.logout()
    })

    // go back to the employee who submitted the expense to make sure the expense has reached the next stage
    cy.visit('/login')
    cy.get('input[name=email]').type('admin@admin.com')
    cy.get('input[name=password]').type('admin')
    cy.get('button[type=submit]').click()
    cy.wait(1000)

    cy.visit('/1/dashboard')
    cy.get('[data-cy=expense-1-status-accounting_approval]').should('exist')
  })

  it('should let an employeee log an expense and let his manager reject it', function () {
    cy.loginLegacy()

    cy.createCompany()

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'user', true)

    cy.visit('/1/employees/1')

    // assign Michael scott as the manager so we can validate a task has been created for the manager later on
    cy.assignManager('scott')

    // grant the accountant right to this manager, so he can also give the final acceptance
    cy.grantAccountantRight('scott', 2)

    // now go back to the dashboard and create an expense
    cy.createExpense('restaurant', 123)

    // now login to the Michael Scott's account to check the notification
    cy.visit('/1/account/employees')
    cy.get("[name='Michael Scott']").invoke('attr', 'data-invitation-link').then((link) => {
      cy.acceptInvitationLinkAndGoToDashboard('admin2020', link)

      // click on the expense to see it and reject it
      cy.visit('/1/dashboard/manager')
      cy.get('[data-cy=expense-cta-1]').click()
      cy.get('[data-cy=expense-reject-button]').click()
      cy.get('[data-cy=expense-rejection-cancel-modal]').click()
      cy.get('[data-cy=expense-reject-button]').click()
      cy.get('[data-cy=rejection-reason-textarea').type('Way too much money')
      cy.get('[data-cy=submit-rejection]').click()

      cy.visit('/1/employees/2/logs')
      cy.contains('Rejected an expense')

      // check that the manager tab does not contain expenses anymore
      cy.visit('/1/dashboard/manager')
      cy.get('[data-cy=expense-list-blank-state]').should('exist')

      //

      //
      cy.logout()
    })

    // go back to the employee who submitted the expense to make sure the expense has reached the next stage
    cy.visit('/login')
    cy.get('input[name=email]').type('admin@admin.com')
    cy.get('input[name=password]').type('admin')
    cy.get('button[type=submit]').click()
    cy.wait(1000)

    cy.visit('/1/dashboard')
    cy.get('[data-cy=expense-item-1]').should('not.exist')
  })
})
