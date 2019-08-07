describe('Employee - Assign employee statuses', function () {
  it('should assign an employee status and remove it as administrator', function () {
    cy.login()

    cy.createCompany()

    cy.createEmployeeStatus('Dunder Mifflin')

    cy.visit('/1/employees/1')

    // Open the modal
    cy.get('[data-cy=open-status-modal-blank]').click()
    cy.get('[data-cy=list-status-3]').click()
    cy.get('[data-cy=status-name-right-permission]').contains('Dunder Mifflin')
    cy.hasAuditLog('Assigned the employee status called Dunder Mifflin', '/1/employees/1')
    cy.hasEmployeeLog('admin@admin.com assigned the employee status called Dunder Mifflin.', '/1/employees/1')

    // Open the modal to remove the assignment
    cy.get('[data-cy=open-status-modal').click()
    cy.get('[data-cy=status-reset-button]').click()
    cy.get('[data-cy=open-status-modal-blank]').should('not.contain', 'Dunder Mifflin')
    cy.hasAuditLog('Removed the employee status called Dunder Mifflin from', '/1/employees/1')
    cy.hasEmployeeLog('admin@admin.com removed the employee status called Dunder Mifflin', '/1/employees/1')
  })

  it('should assign an employee status and remove it as hr', function () {
    cy.login()

    cy.createCompany()

    cy.createEmployeeStatus('Dunder Mifflin')

    cy.changePermission(1, 200)
    cy.visit('/1/employees/1')

    // Open the modal
    cy.get('[data-cy=open-status-modal-blank]').click()
    cy.get('[data-cy=list-status-3]').click()
    cy.get('[data-cy=status-name-right-permission]').contains('Dunder Mifflin')
    cy.hasEmployeeLog('admin@admin.com assigned the employee status called Dunder Mifflin.', '/1/employees/1')

    // Open the modal to remove the assignment
    cy.get('[data-cy=open-status-modal').click()
    cy.get('[data-cy=status-reset-button]').click()
    cy.get('[data-cy=open-status-modal-blank]').should('not.contain', 'Dunder Mifflin')
    cy.hasEmployeeLog('admin@admin.com removed the employee status called Dunder Mifflin', '/1/employees/1')
  })

  it('should not let a normal user assign employee status', function () {
    cy.login()

    cy.createCompany()

    cy.changePermission(1, 300)
    cy.visit('/1/employees/1')

    cy.contains('No status set')
    cy.get('[data-cy=open-status-modal-blank]').should('not.exist')
  })
})
