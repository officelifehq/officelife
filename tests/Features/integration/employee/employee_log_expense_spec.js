describe('Employee - Log an expense', function () {
  it('should let an employeee log an expense and assign a task to his manager', function () {
    cy.login()

    cy.createCompany()

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true)

    cy.visit('/1/employees/1')

    // assign Michael scott as the manager so we can validate a task has been created for the manager later on
    cy.get('[data-cy=add-hierarchy-button]').click()
    cy.get('[data-cy=add-manager-button]').click()
    cy.get('[data-cy=search-manager]').type('scott')
    cy.get('[data-cy=potential-manager-button').click()

    // now go back to the dashboard and create an expense
    cy.get('[data-cy=create-expense-cta]').click()
    cy.get('[data-cy=expense-create-cancel]').click()
    cy.get('[data-cy=create-expense-cta]').click()
    cy.get('[data-cy=expense-title]').type('expense 1')

    cy.get('[data-cy=submit-expense]').click()

    // Open the modal
    cy.get('[data-cy=open-status-modal-blank]').click()
    cy.get('[data-cy=list-status-1]').click()
    cy.get('[data-cy=status-name-right-permission]').contains('Dunder Mifflin')
    cy.hasAuditLog('Assigned the employee status called Dunder Mifflin', '/1/employees/1')
    cy.hasEmployeeLog('Assigned the employee status called Dunder Mifflin.', '/1/employees/1')

    // Open the modal to remove the assignment
    cy.get('[data-cy=open-status-modal').click()
    cy.get('[data-cy=status-reset-button]').click()
    cy.get('[data-cy=open-status-modal-blank]').should('not.contain', 'Dunder Mifflin')
    cy.hasAuditLog('Removed the employee status called Dunder Mifflin from', '/1/employees/1')
    cy.hasEmployeeLog('Removed the employee status called Dunder Mifflin', '/1/employees/1')
  })
})
