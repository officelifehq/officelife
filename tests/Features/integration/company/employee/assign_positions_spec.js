describe('Employee - Assign positions', function () {
  it('should assign a position and remove it as administrator', function () {
    cy.login()

    cy.createCompany()

    cy.createTeam('product')

    cy.visit('/1/employees/1')

    // Open the modal to assign a team and select the first line
    cy.get('[data-cy=open-position-modal-blank]').click()
    cy.get('[data-cy=list-position-1]').click()
    cy.get('[data-cy=open-position-modal]').contains('CEO')
    cy.hasAuditLog('Assigned to admin@admin.com the position called CEO', '/1/employees/1')
    cy.hasEmployeeLog('admin@admin.com assigned the position called CEO', '/1/employees/1')

    // Open the modal to assign a team and select the first line
    cy.get('[data-cy=open-position-modal').click()
    cy.get('[data-cy=position-reset-button]').click()
    cy.get('[data-cy=open-position-modal-blank]').should('not.contain', 'CEO')
    cy.hasAuditLog('Removed the position called CEO to admin@admin.com', '/1/employees/1')
    cy.hasEmployeeLog('admin@admin.com removed the position called CEO', '/1/employees/1')
  })

  it('should assign a position and remove it as hr', function () {
    cy.login()

    cy.createCompany()

    cy.createTeam('product')

    cy.changePermission(1, 200)
    cy.visit('/1/employees/1')

    // Open the modal to assign a team and select the first line
    cy.get('[data-cy=open-position-modal-blank]').click()
    cy.get('[data-cy=list-position-1]').click()
    cy.get('[data-cy=open-position-modal]').contains('CEO')
    cy.hasEmployeeLog('admin@admin.com assigned the position called CEO', '/1/employees/1')

    // Open the modal to assign a team and select the first line
    cy.get('[data-cy=open-position-modal').click()
    cy.get('[data-cy=position-reset-button]').click()
    cy.get('[data-cy=open-position-modal-blank]').should('not.contain', 'CEO')
    cy.hasEmployeeLog('admin@admin.com removed the position called CEO', '/1/employees/1')
  })

  it('should not let a normal user assign teams', function () {
    cy.login()

    cy.createCompany()

    cy.changePermission(1, 300)
    cy.visit('/1/employees/1')

    cy.contains('No position set')
    cy.get('[data-cy=open-position-modal-blank]').should('not.exist')
  })
})
